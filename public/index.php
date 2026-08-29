<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGIBET - Software de Gestión para Almacenes de Telas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark-custom fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <span class="text-primary-custom">SIGI</span>BET
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-toggle="target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a class="nav-link px-3" href="#caracteristicas">Características</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="#beneficios">Beneficios</a>
                    </li>
                    <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                        <a class="btn btn-primary-custom px-4 py-2 fw-semibold" href="../views/auth/login.php">Iniciar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section d-flex align-items-center">
        <div class="container text-center text-lg-start">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0 hero-content">
                    <h1 class="display-4 fw-bold text-white mb-4">La solución definitiva para tu <span class="text-primary-custom">Almacén de Telas</span></h1>
                    <p class="lead text-light mb-4 pb-2">Gestiona tu inventario con precisión, automatiza tu facturación POS y lleva el control total de tu negocio desde una única plataforma profesional e intuitiva.</p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start gap-3">
                        <a href="#caracteristicas" class="btn btn-outline-light px-4 py-2">Descubrir más</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image-wrapper shadow-lg rounded-4 overflow-hidden">
                        <!-- Aquí puede ir una imagen de mockup del software -->
                        <div class="mockup-placeholder d-flex align-items-center justify-content-center bg-dark text-muted">
                            <i class="bi bi-display fa-3x"></i>
                            <span class="ms-3 fs-4">Interfaz del Software</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="caracteristicas" class="features-section py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5 pb-2">
                <h2 class="fw-bold text-dark mb-3">Diseñado específicamente para el sector textil</h2>
                <p class="text-muted w-75 mx-auto">Herramientas potentes y fáciles de usar para optimizar las operaciones diarias de tu almacén de telas.</p>
            </div>
            <div class="row g-4">
                <!-- Feature 1 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card feature-card h-100 border-0 shadow-sm p-4">
                        <div class="feature-icon bg-primary-light text-primary-custom mb-4 rounded-3 d-inline-flex align-items-center justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-box-seam" viewBox="0 0 16 16">
                                <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2zm3.564 1.426L5.596 5 8 5.961 14.154 3.5zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z"/>
                            </svg>
                        </div>
                        <h4 class="fw-bold mb-3">Gestión de Inventario</h4>
                        <p class="text-muted mb-0">Control exacto de metrajes, colores y tipos de tela. Alertas de stock bajo y reportes detallados en tiempo real.</p>
                    </div>
                </div>
                <!-- Feature 2 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card feature-card h-100 border-0 shadow-sm p-4">
                        <div class="feature-icon bg-primary-light text-primary-custom mb-4 rounded-3 d-inline-flex align-items-center justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-receipt" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1.5 3a.5.5 0 0 0-.5.5v10.5a.5.5 0 0 0 1 0V4h12v10a.5.5 0 0 0 1 0V3.5a.5.5 0 0 0-.5-.5z"/>
                                <path d="M3 5h10v1H3zm0 2h10v1H3zm0 2h10v1H3zm0 2h10v1H3zm0 2h10v1H3z"/>
                            </svg>
                        </div>
                        <h4 class="fw-bold mb-3">Facturación POS</h4>
                        <p class="text-muted mb-0">Sistema de punto de venta ágil y eficiente. Generación de facturas de forma rápida para reducir tiempos de espera en caja.</p>
                    </div>
                </div>
                <!-- Feature 3 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card feature-card h-100 border-0 shadow-sm p-4">
                        <div class="feature-icon bg-primary-light text-primary-custom mb-4 rounded-3 d-inline-flex align-items-center justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-graph-up-arrow" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5"/>
                            </svg>
                        </div>
                        <h4 class="fw-bold mb-3">Reportes Estratégicos</h4>
                        <p class="text-muted mb-0">Visualiza el rendimiento de tu negocio. Análisis de ventas, productos más vendidos y métricas clave para la toma de decisiones.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 border-top border-secondary">
        <div class="container text-center">
            <p class="mb-0 text-muted">&copy; 2026 SIGIBET. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
