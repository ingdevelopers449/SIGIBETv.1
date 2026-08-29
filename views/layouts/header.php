<?php
// Requerir el AuthController para verificar la sesión
require_once __DIR__ . '/../../controllers/auth/AuthController.php';

// Obtener sesión de usuario
$usuario = AuthController::usuarioActual();
if (!$usuario) {
    header('Location: ../../views/auth/login.php');
    exit;
}

$id_rol_actual = $usuario['id_rol'] ?? 2;
$nombre_rol  = ($id_rol_actual == '1') ? 'Administrador' : 'Empleado';
$usuario_nombre = $usuario['nombre'] ?? 'Usuario';
$usuario_inicial = strtoupper(substr($usuario_nombre, 0, 1));

// Tema Actualizado según diseño (Azul y Oscuro)
$tema = [
    'color_primario' => '#3E3A36', // Gris cálido para la base (sidebar)
    'color_secundario' => '#3DA9E0', // Azul como acento y secundario
    'color_fondo' => '#F5F5F5' // Gris claro para fondo
];
$color_rol = ($id_rol_actual == '1') ? '#F4C430' : '#3DA9E0'; // Amarillo para rol admin

// Lógica de Breadcrumbs
$c = $_GET['c'] ?? 'producto';
$a = $_GET['a'] ?? 'index';
$current_page_key = $c . '_' . $a;

$breadcrumb_map = [
    'dashboard_index'       => [['icono'=>'fa-chart-pie',              'label'=>'Dashboard']],
    'venta_index'           => [['icono'=>'fa-file-invoice-dollar',    'label'=>'Ventas'], ['icono'=>'fa-cash-register', 'label'=>'Facturación POS']],
    'venta_historial'       => [['icono'=>'fa-file-invoice-dollar',    'label'=>'Ventas'], ['icono'=>'fa-list',          'label'=>'Historial']],
    'cliente_index'         => [['icono'=>'fa-users',                  'label'=>'Clientes']],
    'producto_index'        => [['icono'=>'fa-box-open',               'label'=>'Catálogo'], ['icono'=>'fa-box-open',    'label'=>'Productos']],
    'inventario_index'      => [['icono'=>'fa-warehouse',              'label'=>'Inventario'], ['icono'=>'fa-cubes',     'label'=>'Dashboard']],
    'producto_stock'        => [['icono'=>'fa-box-open',               'label'=>'Catálogo'], ['icono'=>'fa-cubes',       'label'=>'Inventario']],
    'usuario_listar'        => [['icono'=>'fa-user-shield',            'label'=>'Administración'], ['icono'=>'fa-users','label'=>'Usuarios']],
    'configuracion_index'   => [['icono'=>'fa-building',               'label'=>'Administración'], ['icono'=>'fa-building',   'label'=>'Empresa']],
    'configuracion_colores' => [['icono'=>'fa-palette',                'label'=>'Administración'], ['icono'=>'fa-palette',    'label'=>'Colores']],
    'reporte_index'         => [['icono'=>'fa-chart-line',             'label'=>'Reportes']],
];

$breadcrumbs = $breadcrumb_map[$current_page_key] ?? [['icono'=>'fa-circle', 'label'=>ucfirst($c)]];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGIBET - Almacén de Telas</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6.5.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../public/style.css">
    <!-- Color Theme CSS -->
    <style>
        :root {
            --color-primario: <?= $tema['color_primario'] ?>;
            --color-secundario: <?= $tema['color_secundario'] ?>;
            --color-fondo: <?= $tema['color_fondo'] ?>;
        }
        html, body {
            height: 100%;
            margin: 0;
            background-color: var(--color-fondo);
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }
        .app-container {
            height: 100vh;
            width: 100vw;
            overflow: hidden;
        }
        .app-sidebar {
            width: 260px;
            height: 100vh;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            background: linear-gradient(180deg, var(--bs-dark) 0%, #292623 100%);
            transition: all 0.3s ease;
            z-index: 1040;
        }
        
        .sidebar-nav .nav-link {
            color: rgba(255,255,255,0.75);
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        
        .sidebar-nav .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.05);
            border-left: 4px solid var(--bs-primary);
        }
        
        .sidebar-nav .nav-link.active {
            color: #fff;
            background: var(--bs-primary);
            border-left: 4px solid #fff;
            box-shadow: 0 4px 10px rgba(61, 169, 224, 0.3);
        }
        .app-main {
            height: 100vh;
            overflow: hidden;
        }
        .app-content {
            overflow-y: auto;
            background-color: var(--color-fondo);
        }
        @media (max-width: 768px) {
            .app-sidebar {
                position: fixed;
                left: -260px;
                box-shadow: 5px 0 15px rgba(0,0,0,0.2) !important;
            }
            .app-sidebar.show {
                left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="app-container d-flex">
        
        <!-- Sidebar -->
        <?php 
        if (AuthController::esAdmin()) {
            include __DIR__ . '/sidebaradmin.php'; 
        } else {
            // Asumiendo que el sidebar de empleado está en partials o Layouts
            if(file_exists(__DIR__ . '/sidebarseller.php')) {
                include __DIR__ . '/sidebarseller.php'; 
            } else {
                include __DIR__ . '/../partials/sidebar.php'; 
            }
        }
        ?>

        <!-- Main Wrapper -->
        <div class="app-main flex-grow-1 d-flex flex-column overflow-hidden">
            <!-- Topbar -->
            <header class="app-topbar d-flex justify-content-between align-items-center px-4 bg-white border-bottom shadow-sm z-1" style="height: 80px; min-height: 80px;">
                <!-- Botón toggle sidebar móvil -->
                <button class="btn btn-light d-md-none me-2" id="toggleSidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <!-- Breadcrumb dinámico -->
                <nav aria-label="breadcrumb" class="d-none d-md-flex align-items-center">
                    <ol class="breadcrumb mb-0 align-items-center">
                        <!-- Inicio siempre primero -->
                        <li class="breadcrumb-item">
                            <a href="gproductos.php"
                               class="text-decoration-none d-flex align-items-center gap-1"
                               style="color: var(--color-secundario);">
                                <i class="fa-solid fa-house" style="font-size:.8rem;"></i>
                                <span style="font-size:.83rem;">SIGIBET</span>
                            </a>
                        </li>
                        <?php foreach ($breadcrumbs as $i => $crumb): ?>
                            <?php $is_last = ($i === count($breadcrumbs) - 1); ?>
                            <li class="breadcrumb-item <?php echo $is_last ? 'active' : ''; ?>"
                                <?php if ($is_last) echo 'aria-current="page"'; ?>>
                                <?php if ($is_last): ?>
                                    <span class="d-flex align-items-center gap-1 fw-semibold" style="color: var(--color-secundario); font-size:.85rem;">
                                        <i class="fa-solid <?php echo htmlspecialchars($crumb['icono']); ?>" style="font-size:.78rem;"></i>
                                        <?php echo htmlspecialchars($crumb['label']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted d-flex align-items-center gap-1" style="font-size:.83rem;">
                                        <i class="fa-solid <?php echo htmlspecialchars($crumb['icono']); ?>" style="font-size:.75rem;"></i>
                                        <?php echo htmlspecialchars($crumb['label']); ?>
                                    </span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </nav>

                <!-- Perfil de usuario (estático) -->
                <div class="d-flex align-items-center gap-3 ms-auto">
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm text-white fw-bold flex-shrink-0"
                         style="width:40px;height:40px;background:linear-gradient(135deg, var(--color-primario), var(--color-secundario));font-size:1rem;">
                        <?php echo $usuario_inicial; ?>
                    </div>
                    <div class="d-none d-md-block lh-sm">
                        <div class="fw-semibold text-dark" style="font-size:.9rem;"><?php echo htmlspecialchars($usuario_nombre); ?></div>
                        <span class="badge rounded-pill px-2" style="background-color:<?php echo $color_rol; ?>; font-size:.68rem;"><?php echo $nombre_rol; ?></span>
                    </div>
                </div>
            </header>

            <!-- Main Content Area (Scrollable) -->
            <main class="app-content p-4 flex-grow-1 overflow-auto" style="background-color: var(--color-fondo);">
