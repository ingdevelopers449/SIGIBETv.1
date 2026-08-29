<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Usuario.php';

/**
 * CONTROLADOR DE AUTENTICACIÓN
 * 
 * Maneja el inicio de sesión y cierre de sesión.
 */
class AuthController
{
    public static function usuarioActual()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['usuario'] ?? null;
    }

    public static function esAdmin()
    {
        $usuario = self::usuarioActual();
        return ($usuario && isset($usuario['id_rol']) && $usuario['id_rol'] == 1);
    }

    public static function requerirRol(array $rolesPermitidos)
    {
        $usuario = self::usuarioActual();
        if (!$usuario || !in_array($usuario['id_rol'], $rolesPermitidos)) {
            header('HTTP/1.0 403 Forbidden');
            echo json_encode(['success' => false, 'mensaje' => 'Acceso denegado. No tienes permisos para esta acción.']);
            exit;
        }
    }

    public function login()
    {
        // Si ya hay una sesión activa, lo mandamos al dashboard directamente para evitar reprocesos
        if (isset($_SESSION['usuario'])) {
            $destino = ($_SESSION['usuario']['id_rol'] == 1) ? '../../views/admin/gproductos.php' : '../../views/seller/mis_ventas.php';
            header('Location: ' . $destino);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../../views/auth/login.php');
            exit;
        }

        // Permite usar correo o nombre de usuario
        $identificador = trim($_POST['email'] ?? $_POST['usuario'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($identificador) || empty($password)) {
            $_SESSION['alert'] = [
                'icon' => 'warning',
                'title' => 'Campos incompletos',
                'text' => 'Debe ingresar su correo/usuario y contraseña'
            ];
            header('Location: ../../views/auth/login.php');
            exit;
        }

        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->obtenerPorEmail($identificador);

        if (!$usuario || $usuario['estado'] === 'inactivo') {
            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'Usuario no encontrado',
                'text' => 'Los datos son incorrectos o la cuenta está inactiva'
            ];
            header('Location: ../../views/auth/login.php');
            exit;
        }

        // Check if user is blocked
        if ($usuario['estado'] === 'bloqueado') {
            $ultimo_acceso = strtotime($usuario['ultimo_acceso']);
            $ahora = time();
            $diferencia_minutos = round(($ahora - $ultimo_acceso) / 60);

            if ($diferencia_minutos < 15) {
                $minutos_restantes = 15 - $diferencia_minutos;
                $_SESSION['alert'] = [
                    'icon' => 'error',
                    'title' => 'Cuenta bloqueada',
                    'text' => 'Demasiados intentos fallidos. Intente nuevamente en ' . $minutos_restantes . ' minutos.'
                ];
                header('Location: ../../views/auth/login.php');
                exit;
            } else {
                // Time passed, unblock user for this new attempt
                $usuarioModel->resetearIntentosYActualizarAcceso($usuario['id_usuario']);
                $usuario['intentos_fallidos'] = 0;
                $usuario['estado'] = 'activo';
            }
        }

        if (!password_verify($password, $usuario['password_hash'])) {
            $usuarioModel->registrarIntentoFallido($usuario['id_usuario'], $usuario['intentos_fallidos'] ?? 0);

            $intentos_restantes = 2 - ($usuario['intentos_fallidos'] ?? 0);
            if ($intentos_restantes <= 0) {
                $mensaje = 'Su cuenta ha sido bloqueada por seguridad. Espere 15 minutos.';
            } else {
                $mensaje = 'Contraseña incorrecta. Le quedan ' . $intentos_restantes . ' intentos.';
            }

            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'Error de Autenticación',
                'text' => $mensaje
            ];
            header('Location: ../../views/auth/login.php');
            exit;
        }

        // Login successful
        $usuarioModel->resetearIntentosYActualizarAcceso($usuario['id_usuario']);

        session_regenerate_id(true);

        $_SESSION['usuario'] = [
            'id_usuario' => $usuario['id_usuario'],
            'nombre' => $usuario['nombre'],
            'usuario' => $usuario['usuario'],
            'email' => $usuario['email'],
            'id_rol' => $usuario['id_rol']
        ];

        switch ($usuario['id_rol']) {
            case '1':
                header('Location: ../../views/admin/gproductos.php');
                exit;

            case '2':
                header('Location: ../../views/seller/mis_ventas.php');
                exit;

            default:
                $_SESSION['alert'] = [
                    'icon' => 'error',
                    'title' => 'Rol no válido',
                    'text' => 'No se pudo determinar el acceso del usuario'
                ];
                header('Location: ../../views/auth/login.php');
                exit;
        }
    }

    public function logout()
    {  
        session_unset();
        session_destroy();
        header('Location: ../../views/auth/login.php');
        exit;
    }
}

// Solo ejecutar el enrutador si se accede directamente a este archivo, 
// no cuando es requerido (include) desde otros archivos como header.php
if (basename($_SERVER['PHP_SELF']) === 'AuthController.php') {
    $controller = new AuthController();

    $accion = $_GET['accion'] ?? 'login';

    if ($accion === 'logout') {
        $controller->logout();
    } else {
        $controller->login();
    }
}
?>