# 📁 Carpeta `config/` (Configuraciones)

¡Hola! Si estás empezando a revisar el código, la carpeta `config` es un excelente punto de partida. Como su nombre lo indica, aquí se guardan las configuraciones iniciales del sistema.

## ¿Qué vas a encontrar aquí?
Actualmente, el archivo más importante (y el único en la mayoría de los casos) es `database.php`.

### `database.php`
Este archivo es el **puente entre nuestro código PHP y la base de datos** (donde se guarda toda la información: usuarios, productos, ventas, etc.).

**¿Para qué sirve?**
Imagina que el sistema es una casa y la base de datos es una caja fuerte. `database.php` tiene la llave para abrir esa caja fuerte. 

**Partes clave del archivo:**
1. **Credenciales:** Verás variables como `$host`, `$user` (usuario), `$password` (contraseña) y `$bd` (el nombre de la base de datos, en este caso `sigibet_db`). Si alguna vez instalas el sistema en otra computadora o servidor web, **aquí es donde debes cambiar la contraseña y el usuario** para que el sistema funcione.
2. **Conexión (`mysqli`):** Utiliza un código envuelto en un `try { ... } catch { ... }`. Esto significa: *"Intenta conectarte usando las credenciales. Si fallas, muestra un error en la pantalla en lugar de colapsar en silencio"*.
3. **Zona Horaria:** Suele tener un `date_default_timezone_set('America/Bogota');` para que las fechas y horas del sistema sean correctas.

## ¿Quién usa esta carpeta?
Prácticamente **todos los Modelos** (los archivos en la carpeta `models/`). Cada vez que el sistema necesita guardar un cliente o leer los productos, un modelo llama a este archivo `database.php` para poder hablar con la base de datos.
