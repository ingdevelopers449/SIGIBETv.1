# 📁 Carpeta `models/` (Modelos)

¡Bienvenido a la capa de datos! En el patrón MVC (Modelo-Vista-Controlador), **los Modelos son los únicos que tienen permiso para hablar con la base de datos.**

## ¿Qué es un Modelo?
Imagina que un Modelo es como el bibliotecario de tu sistema. Si tú (el Controlador) necesitas el libro de "Ventas de hoy", no vas tú mismo al sótano a buscarlo. Se lo pides al bibliotecario (el Modelo), y él te lo trae. 

## Archivos que vas a encontrar aquí
Cada archivo representa una "entidad" o "tabla" en tu base de datos:

- **`Usuario.php`**: Guarda, edita, elimina o busca a las personas que pueden iniciar sesión en el sistema.
- **`Producto.php`**: Se encarga del inventario de telas. Guarda nombres, metros disponibles, precios, etc.
- **`Venta.php`**: Registra cuando un cliente compra algo.
- **`Cliente.php`**: Guarda la información de los compradores (nombre, documento, teléfono).
- **`Movimiento.php`**: Registra las entradas y salidas de inventario.
- **`Auditoria.php`**: Es el "chismoso" del sistema. Guarda un historial de quién hizo qué (ej. "Juan editó el producto Tela Azul").
- **`Backup.php`**: Se encarga de hacer copias de seguridad de la base de datos.
- **`Configuracion.php`**: Para parámetros generales del negocio (ej. el nombre del almacén, el IVA).
- **`Reporte.php`**: Hace las consultas matemáticas difíciles (ej. "dime cuánto ganamos este mes").

## ¿Cómo están hechos por dentro?
Si abres uno de estos archivos (por ejemplo `Usuario.php`), verás que es una **Clase (`class Usuario`)**.
Dentro de la clase hay "funciones" (llamadas **métodos**) que hacen las tareas:
1. `obtenerTodos()`: Hace un `SELECT * FROM usuarios` y devuelve una lista.
2. `registrar($datos)`: Hace un `INSERT INTO usuarios ...` para crear uno nuevo.
3. `actualizar($id, $datos)`: Hace un `UPDATE usuarios SET ...` para editar.

**Para el programador novato:**
- Si necesitas agregar una nueva columna a una tabla (por ejemplo, el "cumpleaños" del usuario), **aquí es donde debes venir a modificar la consulta SQL** (el `INSERT` y el `UPDATE`).
- ¡Los modelos NUNCA imprimen HTML (`echo "<h1>..."`)! Solo procesan datos y los devuelven.
