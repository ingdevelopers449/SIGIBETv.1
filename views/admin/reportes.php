<?php
// Incluir el header
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid py-2">
    <!-- Encabezado y Filtros -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <h2 class="h4 mb-0 text-gray-800 fw-bold d-flex align-items-center">
            <i class="fa-solid fa-chart-line text-primary-custom me-2"></i> Historial y Reportes
        </h2>
        
        <form id="form-filtros" class="d-flex gap-2">
            <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" title="Fecha Inicio">
            <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" title="Fecha Fin">
            <button type="submit" class="btn btn-primary d-flex align-items-center gap-2" id="btn-filtrar">
                <i class="fa-solid fa-filter"></i> Filtrar
            </button>
        </form>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-0" style="border-left: 4px solid var(--bs-warning); border-radius: 12px; transition: transform 0.3s ease;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #4a5568; letter-spacing: 0.5px;">
                                Valor del Inventario</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="resumen-inventario">$0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x" style="color: rgba(244, 196, 48, 0.3);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-0" style="border-left: 4px solid var(--bs-primary); border-radius: 12px; transition: transform 0.3s ease;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #4a5568; letter-spacing: 0.5px;">
                                Ingresos (Ventas)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="resumen-ingresos">$0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x" style="color: rgba(61, 169, 224, 0.2);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-0" style="border-left: 4px solid var(--bs-danger); border-radius: 12px; transition: transform 0.3s ease;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #4a5568; letter-spacing: 0.5px;">
                                N° de Ventas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="resumen-cantidad">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x" style="color: rgba(232, 93, 93, 0.2);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold" style="color: var(--bs-primary);">Ingresos por Periodo</h6>
                </div>
                <div class="card-body">
                    <canvas id="chart-ingresos"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold" style="color: var(--bs-warning);">Top Productos Más Vendidos</h6>
                </div>
                <div class="card-body">
                    <canvas id="chart-top-productos"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial de Ventas -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-dark">Historial de Transacciones</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tabla-historial">
                    <thead class="table-light text-muted" style="font-size: 0.85rem;">
                        <tr>
                            <th class="ps-4">Factura</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Productos (Cant)</th>
                            <th>Método Pago</th>
                            <th class="text-end pe-4">Total</th>
                        </tr>
                    </thead>
                    <tbody id="historial-tbody">
                        <tr><td colspan="6" class="text-center py-4">Cargando datos...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let chartIngresos = null;
    let chartTop = null;

    const formFiltros = document.getElementById('form-filtros');
    const tbodyHistorial = document.getElementById('historial-tbody');
    const btnFiltrar = document.getElementById('btn-filtrar');
    
    // Elementos de resumen
    const resInv = document.getElementById('resumen-inventario');
    const resIng = document.getElementById('resumen-ingresos');
    const resCant = document.getElementById('resumen-cantidad');

    // Cargar datos iniciales
    cargarDatos();

    formFiltros.addEventListener('submit', function(e) {
        e.preventDefault();
        cargarDatos();
    });

    function cargarDatos() {
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;
        
        btnFiltrar.disabled = true;
        btnFiltrar.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Cargando...';

        const url = `../../controllers/ReporteController.php?action=obtenerDatosAjax&fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    actualizarResumen(data.resumen);
                    actualizarGraficos(data.grafico_ingresos, data.grafico_top);
                    actualizarTabla(data.historial);
                } else {
                    console.error(data.mensaje);
                    if(typeof Swal !== 'undefined') Swal.fire('Error', data.mensaje, 'error');
                }
            })
            .catch(err => console.error("Error obteniendo datos:", err))
            .finally(() => {
                btnFiltrar.disabled = false;
                btnFiltrar.innerHTML = '<i class="fa-solid fa-filter"></i> Filtrar';
            });
    }

    function formatMoneda(valor) {
        return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(valor);
    }

    function actualizarResumen(resumen) {
        resInv.innerText = formatMoneda(resumen.valor_inventario);
        resIng.innerText = formatMoneda(resumen.ingresos_totales);
        resCant.innerText = resumen.total_ventas;
    }

    function actualizarTabla(historial) {
        tbodyHistorial.innerHTML = '';
        
        if (historial.length === 0) {
            tbodyHistorial.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-muted">
                <i class="fa-solid fa-folder-open fa-3x mb-3 opacity-50"></i><br>
                No se encontraron resultados para los filtros aplicados.
            </td></tr>`;
            return;
        }

        historial.forEach(v => {
            const tr = document.createElement('tr');
            
            // Construir tooltip de detalles
            let detallesTexto = v.detalles.map(d => `- ${d.nombre} (x${d.cantidad})`).join('\n');
            
            tr.innerHTML = `
                <td class="ps-4 fw-bold text-dark"><span class="badge bg-light text-dark border">${v.codigo_factura}</span></td>
                <td>${new Date(v.fecha).toLocaleString('es-CO')}</td>
                <td><i class="fa-solid fa-user text-muted me-1"></i> ${v.usuario_nombre}</td>
                <td>
                    <span class="text-primary fw-bold cursor-pointer" title="${detallesTexto}" style="cursor:help;">
                        ${v.total_articulos || 0} ítems
                    </span>
                </td>
                <td><span class="badge bg-secondary">${v.metodo_pago}</span></td>
                <td class="text-end pe-4 fw-bold" style="color: var(--bs-primary);">${formatMoneda(v.total)}</td>
            `;
            tbodyHistorial.appendChild(tr);
        });
    }

    function actualizarGraficos(datosIngresos, datosTop) {
        // Colores base (Gris, Azul, Amarillo, Coral)
        const colorAzul = '#3DA9E0';
        const colorAmarillo = '#F4C430';
        const colorCoral = '#E85D5D';
        const colorCarbon = '#3E3A36';

        // 1. Gráfico Ingresos (Líneas)
        const labelsIngresos = datosIngresos.map(d => d.fecha_corta);
        const valoresIngresos = datosIngresos.map(d => d.ingresos);

        const ctxIngresos = document.getElementById('chart-ingresos').getContext('2d');
        if (chartIngresos) chartIngresos.destroy();
        
        chartIngresos = new Chart(ctxIngresos, {
            type: 'line',
            data: {
                labels: labelsIngresos.length ? labelsIngresos : ['Sin datos'],
                datasets: [{
                    label: 'Ingresos Diarios',
                    data: valoresIngresos.length ? valoresIngresos : [0],
                    borderColor: colorAzul,
                    backgroundColor: 'rgba(61, 169, 224, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: colorAzul
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: function(value) { return '$' + value; } } }
                }
            }
        });

        // 2. Gráfico Top Productos (Barras Horizontales)
        const labelsTop = datosTop.map(d => d.nombre.substring(0,20) + '...');
        const valoresTop = datosTop.map(d => d.total_vendido);

        const ctxTop = document.getElementById('chart-top-productos').getContext('2d');
        if (chartTop) chartTop.destroy();

        chartTop = new Chart(ctxTop, {
            type: 'bar',
            data: {
                labels: labelsTop.length ? labelsTop : ['Sin datos'],
                datasets: [{
                    label: 'Unidades Vendidas',
                    data: valoresTop.length ? valoresTop : [0],
                    backgroundColor: [colorAmarillo, colorAzul, colorCoral, colorCarbon, '#858796'],
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
