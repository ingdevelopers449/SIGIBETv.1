<?php
// Protegemos la vista en caso de que alguien intente acceder a la URL manualmente
require_once __DIR__ . '/../../controllers/UsuarioController.php';
$uc = new UsuarioController(); // Esto ejecutará la validación de rol del constructor y redirigirá si no es admin

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid py-2">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0 text-gray-800 fw-bold">
            <i class="fa-solid fa-user-shield text-primary-custom me-2"></i> Gestión de Usuarios
        </h2>
        <button class="btn btn-primary fw-bold" onclick="abrirModalForm()">
            <i class="fa-solid fa-user-plus me-1"></i> Nuevo Usuario
        </button>
    </div>

    <!-- Alertas -->
    <div class="alert alert-info border-0 shadow-sm" style="background-color: rgba(61, 169, 224, 0.1); color: var(--bs-primary);">
        <i class="fa-solid fa-circle-info me-2"></i> <strong>Política de Seguridad:</strong> El sistema permite un máximo de <strong>2 usuarios activos</strong> por rol simultáneamente.
    </div>

    <!-- Tabla de Usuarios -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted">
                        <tr>
                            <th class="ps-4">Usuario</th>
                            <th>Nombre</th>
                            <th>Rol</th>
                            <th>Creación</th>
                            <th class="text-center">Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-usuarios">
                        <tr><td colspan="6" class="text-center py-4 text-muted"><i class="fa-solid fa-spinner fa-spin me-2"></i> Cargando usuarios...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Formulario Usuario -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header text-white rounded-top-4 border-0" style="background: linear-gradient(135deg, var(--bs-primary), #2C87B8);">
                <h5 class="modal-title fw-bold" id="modalUsuarioTitulo">Nuevo Usuario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-usuario">
                <div class="modal-body p-4">
                    <input type="hidden" id="usr_id" name="id" value="0">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">Nombre Completo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="usr_nombre" name="nombre" required>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Nombre de Usuario <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="usr_usuario" name="usuario" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Teléfono</label>
                            <input type="text" class="form-control" id="usr_telefono" name="telefono">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">Correo Electrónico</label>
                        <input type="email" class="form-control" id="usr_email" name="email">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Rol <span class="text-danger">*</span></label>
                            <select class="form-select" id="usr_rol_id" name="rol_id" required>
                                <option value="2">Empleado</option>
                                <option value="1">Administrador</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Estado <span class="text-danger">*</span></label>
                            <select class="form-select" id="usr_estado" name="estado" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold text-muted small">Contraseña <span class="text-danger" id="req-password">*</span></label>
                        <input type="password" class="form-control" id="usr_password" name="password" placeholder="Dejar en blanco para mantener la actual en edición">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4" id="btn-guardar-usr">Guardar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let modalForm;

document.addEventListener("DOMContentLoaded", function() {
    modalForm = new bootstrap.Modal(document.getElementById('modalUsuario'));
    cargarUsuarios();

    document.getElementById('form-usuario').addEventListener('submit', function(e) {
        e.preventDefault();
        guardarUsuario();
    });
});

function cargarUsuarios() {
    const tbody = document.getElementById('tabla-usuarios');
    
    fetch('../../controllers/UsuarioController.php?action=listarAjax')
        .then(res => res.json())
        .then(data => {
            tbody.innerHTML = '';
            
            data.forEach(u => {
                const tr = document.createElement('tr');
                // Estado visual
                let estadoBadge = u.estado == 1 
                    ? '<span class="badge bg-success bg-opacity-10 text-success border border-success"><i class="fa-solid fa-circle me-1" style="font-size:0.5rem"></i> Activo</span>'
                    : '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger"><i class="fa-solid fa-ban me-1" style="font-size:0.5rem"></i> Inactivo</span>';
                
                // Rol badge
                let rolBadge = u.rol_id == 1 
                    ? `<span class="badge" style="background-color: var(--bs-warning); color: #000;">${u.nombre_rol}</span>`
                    : `<span class="badge bg-secondary">${u.nombre_rol}</span>`;

                tr.innerHTML = `
                    <td class="ps-4 fw-bold text-dark">@${u.usuario}</td>
                    <td>
                        <div>${u.nombre}</div>
                        <small class="text-muted">${u.email || 'Sin correo'}</small>
                    </td>
                    <td>${rolBadge}</td>
                    <td class="text-muted small">${new Date(u.fecha_creacion).toLocaleDateString('es-CO')}</td>
                    <td class="text-center">
                        <div class="form-check form-switch d-inline-block">
                            <input class="form-check-input cursor-pointer" type="checkbox" role="switch" 
                                ${u.estado == 1 ? 'checked' : ''} 
                                onchange="cambiarEstado(${u.id}, this.checked)"
                                title="Cambiar estado">
                        </div>
                    </td>
                    <td class="text-end pe-4">
                        <button class="btn btn-sm btn-primary" title="Editar" onclick='abrirModalForm(${JSON.stringify(u).replace(/'/g, "&apos;")})'>
                            <i class="fa-solid fa-pen"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(err => {
            console.error(err);
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-danger">Error al cargar usuarios</td></tr>';
        });
}

function abrirModalForm(usuario = null) {
    document.getElementById('form-usuario').reset();
    const reqPassword = document.getElementById('req-password');
    const inputPassword = document.getElementById('usr_password');

    if (usuario) {
        document.getElementById('modalUsuarioTitulo').innerText = 'Editar Usuario';
        document.getElementById('usr_id').value = usuario.id;
        document.getElementById('usr_nombre').value = usuario.nombre;
        document.getElementById('usr_usuario').value = usuario.usuario;
        document.getElementById('usr_telefono').value = usuario.telefono;
        document.getElementById('usr_email').value = usuario.email;
        document.getElementById('usr_rol_id').value = usuario.rol_id;
        document.getElementById('usr_estado').value = usuario.estado;
        
        reqPassword.style.display = 'none'; // Contraseña opcional al editar
        inputPassword.removeAttribute('required');
    } else {
        document.getElementById('modalUsuarioTitulo').innerText = 'Nuevo Usuario';
        document.getElementById('usr_id').value = 0;
        document.getElementById('usr_estado').value = 1;
        
        reqPassword.style.display = 'inline'; // Contraseña obligatoria al crear
        inputPassword.setAttribute('required', 'true');
    }
    modalForm.show();
}

function guardarUsuario() {
    const form = document.getElementById('form-usuario');
    const formData = new FormData(form);
    const btn = document.getElementById('btn-guardar-usr');
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Guardando...';

    fetch('../../controllers/UsuarioController.php?action=guardarAjax', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire('¡Éxito!', data.mensaje, 'success');
            modalForm.hide();
            cargarUsuarios();
        } else {
            Swal.fire('Atención', data.mensaje, 'warning');
        }
    })
    .catch(err => console.error(err))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = 'Guardar Usuario';
    });
}

function cambiarEstado(id, activo) {
    const nuevoEstado = activo ? 1 : 0;
    const formData = new FormData();
    formData.append('id', id);
    formData.append('estado', nuevoEstado);

    fetch('../../controllers/UsuarioController.php?action=cambiarEstadoAjax', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Estado Actualizado',
                icon: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
        } else {
            Swal.fire('Error', data.mensaje, 'error');
            cargarUsuarios(); // Restaurar el switch visualmente
        }
    })
    .catch(err => {
        console.error(err);
        cargarUsuarios();
    });
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
