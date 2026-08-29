<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/Reporte.php';

/**
 * CONTROLADOR DE REPORTES E HISTORIAL
 */
class ReporteController
{
    private Reporte $modelo;

    public function __construct()
    {
        $this->modelo = new Reporte();
    }

    /**
     * Muestra la vista principal de Reportes e Historial
     */
    public function index()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ../../auth/login.php');
            exit;
        }
        
        require_once __DIR__ . '/../views/admin/reportes.php';
    }

    /**
     * Devuelve todos los datos requeridos por la vista en formato JSON
     */
    public function obtenerDatosAjax()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['success' => false, 'mensaje' => 'No autorizado']);
            exit;
        }

        $filtros = [];
        if (!empty($_GET['fecha_inicio'])) $filtros['fecha_inicio'] = $_GET['fecha_inicio'];
        if (!empty($_GET['fecha_fin'])) $filtros['fecha_fin'] = $_GET['fecha_fin'];
        if (!empty($_GET['usuario_id'])) $filtros['usuario_id'] = (int)$_GET['usuario_id'];

        try {
            $resumen = $this->modelo->obtenerResumenGeneral($filtros);
            $ventasPorPeriodo = $this->modelo->obtenerVentasPorPeriodo($filtros);
            $topProductos = $this->modelo->obtenerTopProductos(5, $filtros);
            $historial = $this->modelo->obtenerHistorialVentas($filtros);

            echo json_encode([
                'success' => true,
                'resumen' => $resumen,
                'grafico_ingresos' => $ventasPorPeriodo,
                'grafico_top' => $topProductos,
                'historial' => $historial
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'mensaje' => $e->getMessage()]);
        }
        exit;
    }
}

// Router Simple (Front Controller Embedded)
if (basename($_SERVER['PHP_SELF']) === 'ReporteController.php') {
    $controller = new ReporteController();
    $accion = $_GET['action'] ?? '';

    switch ($accion) {
        case 'obtenerDatosAjax':
            $controller->obtenerDatosAjax();
            break;
        default:
            echo json_encode(['success' => false, 'mensaje' => 'Acción no válida.']);
            exit;
    }
}
