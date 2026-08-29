<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Auditoria.php';

/**
 * MODELO DE VENTAS
 * 
 * Gestiona las operaciones de base de datos para ventas, detalles de venta y actualización de stock.
 */
class Venta
{
    private \mysqli $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    /**
     * Registra una venta completa (Cabecera, Detalle y Movimientos de Inventario).
     * Todo se ejecuta dentro de una transacción para garantizar atomicidad.
     */
    public function registrarVentaCompleta(int $usuarioId, array $carrito, float $subtotal, float $total, string $metodoPago = 'Efectivo', ?int $clienteId = null): array
    {
        try {
            // Iniciar Transacción
            $this->conn->begin_transaction();

            // 1. Insertar Cabecera de la Venta
            $codigoFactura = 'FAC-' . time(); // Generador simple de código
            $queryVenta = "INSERT INTO ventas (codigo_factura, usuario_id, cliente_id, subtotal, total, metodo_pago) VALUES (?, ?, ?, ?, ?, ?)";
            $stmtVenta = $this->conn->prepare($queryVenta);
            if (!$stmtVenta) throw new Exception("Error al preparar venta: " . $this->conn->error);
            
            $stmtVenta->bind_param("siidds", $codigoFactura, $usuarioId, $clienteId, $subtotal, $total, $metodoPago);
            if (!$stmtVenta->execute()) throw new Exception("Error al insertar venta: " . $stmtVenta->error);
            
            $ventaId = $this->conn->insert_id;
            $stmtVenta->close();

            // Preparar statements para el bucle
            $queryDetalle = "INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)";
            $stmtDetalle = $this->conn->prepare($queryDetalle);
            
            $queryStock = "SELECT stock FROM productos WHERE id = ? FOR UPDATE"; // Bloquear fila para lectura segura
            $stmtStock = $this->conn->prepare($queryStock);

            $queryUpdateStock = "UPDATE productos SET stock = ? WHERE id = ?";
            $stmtUpdateStock = $this->conn->prepare($queryUpdateStock);

            $queryMovimiento = "INSERT INTO movimientos (producto_id, usuario_id, tipo, cantidad, stock_anterior, stock_nuevo, motivo) VALUES (?, ?, 'SALIDA_VENTA', ?, ?, ?, ?)";
            $stmtMovimiento = $this->conn->prepare($queryMovimiento);

            // 2. Procesar cada ítem del carrito
            foreach ($carrito as $item) {
                $productoId = (int)$item['id'];
                $cantidad = (int)$item['cantidad'];
                $precioUnitario = (float)$item['precio'];
                $subtotalItem = $cantidad * $precioUnitario;

                // A. Verificar Stock Actual
                $stmtStock->bind_param("i", $productoId);
                $stmtStock->execute();
                $resultStock = $stmtStock->get_result();
                if ($resultStock->num_rows === 0) throw new Exception("Producto ID $productoId no encontrado.");
                
                $rowStock = $resultStock->fetch_assoc();
                $stockActual = (int)$rowStock['stock'];

                if ($stockActual < $cantidad) {
                    throw new Exception("Stock insuficiente para el producto ID $productoId. Disponible: $stockActual, Solicitado: $cantidad.");
                }

                $nuevoStock = $stockActual - $cantidad;

                // B. Insertar Detalle Venta
                $stmtDetalle->bind_param("iiidd", $ventaId, $productoId, $cantidad, $precioUnitario, $subtotalItem);
                if (!$stmtDetalle->execute()) throw new Exception("Error al insertar detalle: " . $stmtDetalle->error);

                // C. Actualizar Stock
                $stmtUpdateStock->bind_param("ii", $nuevoStock, $productoId);
                if (!$stmtUpdateStock->execute()) throw new Exception("Error al actualizar stock: " . $stmtUpdateStock->error);

                // D. Registrar Movimiento
                $motivo = "Venta Factura: " . $codigoFactura;
                $stmtMovimiento->bind_param("iiiiis", $productoId, $usuarioId, $cantidad, $stockActual, $nuevoStock, $motivo);
                if (!$stmtMovimiento->execute()) throw new Exception("Error al registrar movimiento: " . $stmtMovimiento->error);
            }

            $stmtDetalle->close();
            $stmtStock->close();
            $stmtUpdateStock->close();
            $stmtMovimiento->close();

            // Confirmar Transacción
            $this->conn->commit();
            
            // Registrar Auditoría
            Auditoria::registrar('Ventas', 'Registrar Venta', "Factura: $codigoFactura | Total: $total");

            return ['success' => true, 'mensaje' => 'Venta procesada con éxito.', 'venta_id' => $ventaId, 'codigo_factura' => $codigoFactura];

        } catch (Exception $e) {
            // Revertir todo en caso de error
            $this->conn->rollback();
            return ['success' => false, 'mensaje' => $e->getMessage()];
        }
    }
}
