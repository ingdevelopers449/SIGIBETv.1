# 📁 Carpeta `views/` (Vistas)

¡Bienvenido al Front-End del sistema! La carpeta `views` contiene todo lo que **el usuario finalmente ve en su pantalla**. Aquí es donde vive el HTML, combinado con pedacitos de PHP para mostrar datos dinámicos.

## Estructura de la carpeta
Verás que las vistas están organizadas en subcarpetas para mantener el orden:

### 1. `auth/` (Autenticación)
Aquí está el archivo `login.php`. Es la pantalla de bienvenida donde los usuarios ponen su correo/usuario y contraseña.

### 2. `admin/` (Módulos Principales)
Aquí están las pantallas de la aplicación una vez que iniciaste sesión. Encontrarás:
- `usuarios.php`: La tabla para gestionar usuarios.
- `inventario.php` o `productos.php`: Para ver y editar las telas.
- `ventas.php`: El sistema de Punto de Venta (POS) para cobrarle a los clientes.
- `configuracion.php`, `clientes.php`, etc.

### 3. `layouts/` (Las Plantillas Maestras)
**¡Esta es la carpeta más importante para no repetir código!**
En el desarrollo web, todas tus páginas suelen tener el mismo menú superior (Header) y el mismo menú lateral (Sidebar). Sería un dolor de cabeza copiar y pegar ese menú en 20 archivos diferentes (porque si quieres cambiar un botón, tendrías que cambiar los 20 archivos).

Para solucionar eso, SIGIBET usa los **Layouts**:
- **`header.php`**: Tiene las etiquetas de inicio (`<html>`, `<head>`), los enlaces a los archivos CSS, y la barra de navegación superior.
- **`sidebaradmin.php`**: El menú lateral con los botones de "Ventas", "Inventario", etc.
- **`footer.php`**: El pie de página, los scripts de JavaScript (`<script src="...">`) y el cierre del `</body>`.

## ¿Cómo funciona una vista por dentro? (Guía para Novatos)
Si abres `admin/usuarios.php`, no verás un archivo HTML completo. Verás algo como esto:

```php
<?php require_once '../layouts/header.php'; ?>
<?php require_once '../layouts/sidebaradmin.php'; ?>

<!-- Aquí va tu código HTML único para la pantalla de usuarios -->
<div class="container">
    <h1>Gestión de Usuarios</h1>
    <table>...</table>
</div>

<?php require_once '../layouts/footer.php'; ?>
```
**¿Qué está pasando?**
La vista "ensambla" la página usando la función `require_once` de PHP. Es como si armara un rompecabezas: trae la cabeza, pone el cuerpo (el HTML del usuario) y al final le pega los pies. 

**Las Variables en la Vista:**
Si el controlador (quien fue el que cargó esta vista) definió una variable llamada `$lista`, tú puedes usarla directamente en el HTML escribiendo:
`<?php foreach($lista as $item) { echo $item; } ?>`
