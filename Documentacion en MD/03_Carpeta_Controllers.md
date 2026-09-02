# 📁 Carpeta `controllers/` (Controladores)

¡Bienvenido al cerebro de las operaciones! Los controladores son como los gerentes del sistema: no guardan los datos ellos mismos (eso lo hacen los Modelos), ni dibujan la pantalla (eso lo hacen las Vistas), pero **son los que coordinan que el trabajo se haga.**

## ¿Cuál es su función principal?
Cuando un usuario hace clic en "Ver Productos", la petición viaja primero al `ProductoController.php`. El controlador entonces:
1. Le dice al Modelo: *"Oye, búscame todos los productos en la base de datos"*.
2. Recibe la lista del Modelo.
3. Le dice a la Vista: *"Toma, aquí tienes la lista de productos. Ahora dibújalos en una bonita tabla HTML"*.

## Archivos que vas a encontrar aquí
Tendrás un Controlador casi por cada Modelo:
- **`UsuarioController.php`**, **`ProductoController.php`**, **`VentaController.php`**, etc.

Y una subcarpeta muy importante:
- **`auth/AuthController.php`**: Este archivo se encarga de la seguridad. Revisa cosas como: *¿Esta persona inició sesión? ¿La contraseña es correcta? ¿Tiene permiso de Administrador o es solo un Vendedor?*

## ¿Cómo están hechos por dentro? (¡Para Novatos!)
En SIGIBET, los controladores tienen un "truco" especial al final del archivo. 

Si abres uno, verás que arriba hay métodos como `index()`, `listarAjax()` o `guardarAjax()`.
Pero abajo, en el fondo del archivo, hay un bloque de código como este:
```php
if (basename($_SERVER['PHP_SELF']) === 'UsuarioController.php') {
    $controller = new UsuarioController();
    $accion = $_GET['action'] ?? '';
    switch ($accion) { ... }
}
```
**¿Para qué es esto?** 
Esto se llama un enrutador (o Front Controller) integrado. Significa que el frontend (el JavaScript) puede llamar directamente a esta URL: `.../controllers/UsuarioController.php?action=guardarAjax`, y este bloque de código final leerá la palabra `guardarAjax` y ejecutará la función correcta.

**Si eres nuevo y te piden agregar una función nueva (por ejemplo "eliminarUsuario"):**
1. Creas la función dentro de la clase `UsuarioController`.
2. Bajas hasta ese `switch` final y agregas un nuevo `case 'eliminarUsuario': $controller->eliminarUsuario(); break;`.
3. ¡Listo! Tu JavaScript ya puede llamarlo.
