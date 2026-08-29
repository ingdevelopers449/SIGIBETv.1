<?php
// Incluir el header (que automáticamente incluye el topbar y el sidebar correspondiente)
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../controllers/ProductoController.php';

$productoController = new ProductoController();

// Manejo de búsqueda
$busqueda = trim($_GET['q'] ?? '');
$productos = $productoController->index($busqueda);

// SweetAlert2 dependencies are assumed to be loaded in header or here
?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Contenido Específico: Gestión de Productos -->
<div class="container-fluid">
    
    <!-- Encabezado de la página -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h2 class="h4 mb-0 text-gray-800 fw-bold">
            <i class="fa-solid fa-box-open text-primary-custom me-2"></i> Gestión de Productos
        </h2>
        <button type="button" class="btn btn-primary-custom shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoProducto">
            <i class="fa-solid fa-plus fa-sm text-white-50"></i> Nuevo Producto
        </button>
    </div>

    <!-- Barra de Búsqueda -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="gproductos.php" method="GET" class="d-flex" id="searchForm">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar por nombre, referencia o color..." name="q" id="searchInput" value="<?php echo htmlspecialchars($busqueda); ?>" aria-label="Search">
                    <button class="btn btn-primary-custom" type="submit" id="btnSearch">
                        <i class="fas fa-search fa-sm"></i> Buscar
                    </button>
                    <?php if (!empty($busqueda)): ?>
                        <a href="gproductos.php" class="btn btn-outline-secondary">Limpiar</a>
                    <?php endif; ?>
                </div>
            </form>
            <script>
                // Validar búsqueda no vacía
                document.getElementById('searchForm').addEventListener('submit', function(e) {
                    if (document.getElementById('searchInput').value.trim() === '') {
                        e.preventDefault(); // Evitar recarga inútil
                    }
                });
            </script>
        </div>
    </div>

    <!-- Tarjeta Principal: Tabla de Productos -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary-custom">Catálogo de Telas (<?php echo count($productos); ?>)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Ref.</th>
                            <th>Nombre</th>
                            <th>Color</th>
                            <th>Precio Venta</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($productos) > 0): ?>
                            <?php foreach ($productos as $p): ?>
                                <tr>
                                    <td class="fw-bold"><?php echo htmlspecialchars($p['referencia']); ?></td>
                                    <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark border"><i class="fa-solid fa-palette me-1"></i><?php echo htmlspecialchars($p['color']); ?></span>
                                    </td>
                                    <td class="text-success fw-semibold">$<?php echo number_format($p['precio'], 2); ?></td>
                                    <td>
                                        <?php if ($p['stock'] <= $p['stock_minimo']): ?>
                                            <span class="badge bg-danger rounded-pill"><?php echo $p['stock']; ?> (Bajo)</span>
                                        <?php else: ?>
                                            <span class="badge bg-info text-dark rounded-pill"><?php echo $p['stock']; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($p['estado'] == 1): ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <!-- Botón Editar -->
                                            <button type="button" class="btn btn-sm btn-outline-primary" title="Modificar"
                                                onclick='abrirModalEditar(<?php echo json_encode($p); ?>)'>
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            
                                            <!-- Botón Eliminar -->
                                            <a href="../../controllers/ProductoController.php?accion=eliminar&id=<?php echo $p['id']; ?>" 
                                               class="btn btn-sm btn-outline-danger btn-eliminar" title="Eliminar"
                                               data-stock="<?php echo $p['stock']; ?>">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-box-open fa-3x mb-3 text-light"></i>
                                    <h5><?php echo !empty($busqueda) ? 'Producto no encontrado' : 'No hay productos registrados aún.'; ?></h5>
                                    <p><?php echo !empty($busqueda) ? 'Intenta con otra palabra clave.' : 'Haz clic en "Nuevo Producto" para empezar.'; ?></p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nuevo Producto -->
<div class="modal fade" id="modalNuevoProducto" tabindex="-1" aria-labelledby="modalNuevoProductoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalNuevoProductoLabel"><i class="fa-solid fa-plus me-2"></i>Registrar Nueva Tela</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../controllers/ProductoController.php?accion=guardar" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Referencia <span class="text-danger">*</span></label>
                            <input type="text" name="referencia" class="form-control" required placeholder="Ej: TELA-001">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control" required placeholder="Ej: Seda Premium">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Color <span class="text-danger">*</span></label>
                            <input type="text" name="color" class="form-control" required placeholder="Ej: Palo de Rosa">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Categoría</label>
                            <input type="text" name="categoria" class="form-control" placeholder="Ej: Moda Femenina">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Precio Venta <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" name="precio" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Precio Compra</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" name="precio_compra" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cantidad Inicial (Stock) <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control" min="1" required value="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stock Mínimo</label>
                            <input type="number" name="stock_minimo" class="form-control" min="0" value="5">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="2" placeholder="Detalles de la tela..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary-custom"><i class="fa-solid fa-save me-1"></i>Guardar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Producto -->
<div class="modal fade" id="modalEditarProducto" tabindex="-1" aria-labelledby="modalEditarProductoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary-custom text-white">
                <h5 class="modal-title" id="modalEditarProductoLabel"><i class="fa-solid fa-pen-to-square me-2"></i>Modificar Tela</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../controllers/ProductoController.php?accion=actualizar" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_producto" id="edit_id">
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Referencia <span class="text-danger">*</span></label>
                            <input type="text" name="referencia" id="edit_referencia" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Color <span class="text-danger">*</span></label>
                            <input type="text" name="color" id="edit_color" class="form-control" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Categoría</label>
                            <input type="text" name="categoria" id="edit_categoria" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Precio Venta <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" name="precio" id="edit_precio" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Precio Compra</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" name="precio_compra" id="edit_precio_compra" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cantidad (Stock) <span class="text-danger">*</span></label>
                            <input type="number" name="stock" id="edit_stock" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Estado</label>
                            <select name="estado" id="edit_estado" class="form-select">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" id="edit_descripcion" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary-custom"><i class="fa-solid fa-sync me-1"></i>Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Script para abrir modal de edición y llenar datos
function abrirModalEditar(producto) {
    document.getElementById('edit_id').value = producto.id;
    document.getElementById('edit_referencia').value = producto.referencia;
    document.getElementById('edit_nombre').value = producto.nombre;
    document.getElementById('edit_color').value = producto.color;
    document.getElementById('edit_categoria').value = producto.categoria;
    document.getElementById('edit_precio').value = producto.precio;
    document.getElementById('edit_precio_compra').value = producto.precio_compra;
    document.getElementById('edit_stock').value = producto.stock;
    document.getElementById('edit_estado').value = producto.estado;
    document.getElementById('edit_descripcion').value = producto.descripcion;
    
    var modal = new bootstrap.Modal(document.getElementById('modalEditarProducto'));
    modal.show();
}

// SweetAlert2 Alerts
document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_SESSION['alert'])): ?>
        Swal.fire({
            icon: '<?php echo $_SESSION['alert']['icon']; ?>',
            title: '<?php echo $_SESSION['alert']['title']; ?>',
            text: '<?php echo $_SESSION['alert']['text']; ?>',
            confirmButtonColor: '#b56576'
        });
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>

    // Confirmación de eliminación con validación de stock frontend
    const btnEliminar = document.querySelectorAll('.btn-eliminar');
    btnEliminar.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const stock = parseInt(this.getAttribute('data-stock'));
            const href = this.getAttribute('href');

            if (stock > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Acción Denegada',
                    text: 'No se puede eliminar un producto que aún tiene stock disponible (' + stock + ' uds).',
                    confirmButtonColor: '#b56576'
                });
            } else {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            }
        });
    });
});
</script>

<?php
// Incluir el footer (que cierra las etiquetas <main>, <div> y <body> abiertas en el header)
require_once __DIR__ . '/../layouts/footer.php';
?>
