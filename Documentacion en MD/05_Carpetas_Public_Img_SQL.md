# 📁 Carpeta `public/` y Archivos Adicionales

## 1. La carpeta `public/`
En muchos sistemas MVC modernos, la carpeta `public/` es el único lugar al que los visitantes de internet tienen permiso para acceder directamente.

En SIGIBET, aquí encontrarás:
- **`index.php`**: Es la "Puerta de Entrada" principal o la **Landing Page**. Es la primera página que ve un cliente o empleado antes de iniciar sesión. Generalmente tiene información promocional del sistema y un botón de "Iniciar Sesión".
- **`style.css`**: Aquí viven las reglas de diseño (colores, sombras, márgenes). Si quieres cambiar un botón de azul a rojo brillante, o hacer la letra más grande, debes editar este archivo.
- **Podrías encontrar (en un futuro):** Archivos JavaScript globales o recursos de acceso público.

## 2. La carpeta `img/`
Es muy sencilla: es la bodega de imágenes del sistema.
Aquí se guardan:
- El logotipo del sistema (ej. `logo.png`).
- Imágenes de perfil por defecto.
- Fotos de los productos (las telas) si el sistema las requiere.
*(Si necesitas agregar una nueva imagen al menú, primero súbela a esta carpeta).*

## 3. La carpeta `sql/`
Esta carpeta no es usada por el sistema mientras está funcionando, sino **por los programadores**.
- Aquí se guardan los "scripts" o códigos de base de datos (archivos `.sql`).
- Si algún día tu computadora se rompe y necesitas reinstalar todo, abres tu gestor de base de datos (como phpMyAdmin o DBeaver), tomas el archivo que está en la carpeta `sql/` y lo ejecutas. Ese archivo contiene las instrucciones para crear todas las tablas (usuarios, ventas, productos) desde cero.
- **Regla de oro:** Si agregas una tabla nueva a la base de datos, siempre debes actualizar el archivo `.sql` de esta carpeta para que el equipo de trabajo tenga la última versión.
