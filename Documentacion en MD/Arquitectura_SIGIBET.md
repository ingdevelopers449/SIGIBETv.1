# Documentación de Arquitectura - Proyecto SIGIBET

Este documento explica en detalle la estructura técnica del proyecto SIGIBET, basado en el patrón arquitectónico **MVC (Modelo-Vista-Controlador)** utilizando PHP nativo.

---

## 1. Configuraciones (Base de Datos)

El archivo principal de configuración se encuentra en `config/database.php`.

**¿Cómo funciona?**
- Utiliza la extensión `mysqli` orientada a objetos para conectar con la base de datos MySQL/MariaDB.
- Se definen las credenciales en variables simples: `$host`, `$user`, `$password`, `$bd` (nombre de base de datos) y `$port`.
- Mediante un bloque `try...catch`, se intenta crear la instancia de conexión. Si falla, el sistema captura el error y detiene la ejecución mostrando un mensaje. 
- Esta conexión (`$conn`) luego es requerida y utilizada por los modelos para realizar las consultas.

---

## 2. Modelos (`models/`)

Los modelos son clases de PHP (ej. `Usuario.php`, `Producto.php`) encargadas exclusivamente de interactuar con la base de datos. 

**¿Qué hacen?**
- Contienen las operaciones **CRUD** (Crear, Leer, Actualizar, Borrar).
- Encapsulan toda la lógica de negocio y las consultas de SQL. 
- Al instanciar un modelo, este utiliza el archivo de configuración para obtener el objeto de conexión a la base de datos y preparar las sentencias (idealmente *Prepared Statements* para evitar inyección SQL).

---

## 3. Controladores (`controllers/`)

Los controladores (ej. `UsuarioController.php`) son el "cerebro" intermedio. Reciben las peticiones del usuario, procesan la lógica necesaria pidiendo información a los Modelos y finalmente deciden qué Vista mostrar.

**¿Cómo funcionan?**
- **Validación de Seguridad/Sesión:** Verifican si el usuario tiene permisos (ej. `AuthController::esAdmin()`).
- **Enrutamiento Integrado (Front Controller):** Al final de los archivos de controlador, hay un pequeño bloque de enrutamiento:
  ```php
  if (basename($_SERVER['PHP_SELF']) === 'UsuarioController.php') { ... }
  ```
  Esto permite que el controlador procese llamadas AJAX directas. Evalúa un parámetro (por ejemplo `$_GET['action']`) y ejecuta el método interno correspondiente (ej. `listarAjax()`, `guardarAjax()`).
- **Respuesta:** Pueden devolver datos en formato `JSON` (para peticiones AJAX de datatables o fetch) o cargar una interfaz mediante un `require_once` hacia una vista.

---

## 4. Vistas (`views/`) y Layouts (`views/layouts/`)

Las vistas representan la capa de presentación al usuario (HTML/CSS/JS). En lugar de escribir el código base del HTML en cada vista, se implementa el concepto de **Layouts**.

**Layouts (`header.php`, `footer.php`, `sidebaradmin.php`):**
- Son fragmentos de código repetitivo. Por ejemplo, el `header.php` contiene la etiqueta `<head>`, los enlaces a CSS (Bootstrap, fuentes) y quizás la barra de navegación inicial.
- El `footer.php` contiene el cierre de las etiquetas `<body>` y `<html>`, además de los scripts JS.

**Vistas de Módulo (`views/admin/usuarios.php`):**
- Contienen la estructura única de esa pantalla (ej. la tabla de usuarios, el modal de creación).
- Integran los layouts usando `include` o `require_once`.

### El manejo de Variables entre Controlador y Vista
En frameworks modernos existe un motor de plantillas (como Blade o Twig). En SIGIBET (PHP puro), las variables funcionan mediante el **ámbito (scope) global al incluir un archivo**.

**¿Por qué y cómo funciona?**
1. En un método del controlador, digamos `index()`, se declara una variable: 
   `$listaUsuarios = $this->modelo->obtenerTodos();`
2. Inmediatamente después, el controlador hace: 
   `require_once __DIR__ . '/../views/admin/usuarios.php';`
3. Al hacer el `require`, PHP incrusta el código de la vista *dentro del mismo ámbito* en el que se llamó. Por lo tanto, la vista `usuarios.php` puede acceder libremente a la variable `$listaUsuarios` e iterarla con un `foreach` para imprimir HTML.

Este método es muy directo y rápido, aunque requiere que el programador lleve un orden con los nombres de las variables para evitar sobreescribirlas.

---

## 5. El Flujo de Conexión Completo (Resumen)

1. **Petición (Request):** El usuario navega a un módulo, por ejemplo, gestión de usuarios, o envía un formulario por AJAX.
2. **Controlador:** El enrutamiento captura la URL y ejecuta `UsuarioController.php`.
3. **Modelo:** El controlador llama a los métodos de `Usuario.php` (ej. `obtenerTodos()`).
4. **Base de Datos:** El modelo `Usuario` usa `config/database.php` para hacer un `SELECT * FROM usuarios`, y devuelve un array asociativo al controlador.
5. **Vista/JSON:** 
   - Si fue una petición normal, el controlador llama a `views/admin/usuarios.php`, inyectándole los datos. La vista une sus piezas con `header.php` y `footer.php` y se renderiza en el navegador.
   - Si fue una petición AJAX, el controlador imprime los datos con `echo json_encode($datos);` y termina la ejecución, permitiendo que el Frontend en JavaScript actualice la pantalla dinámicamente.
