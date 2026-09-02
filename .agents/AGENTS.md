---
description: Información del proyecto SIGIBET y reglas de desarrollo
---

# Proyecto SIGIBET
SIGIBET (v1.0) es un sistema web desarrollado en PHP con patrón MVC (Modelos, Vistas y Controladores). 

## Estructura del Proyecto
- `config/`: Configuración del sistema (ej. base de datos).
- `controllers/`: Controladores de la aplicación (ej. `ProductoController`, `ConfiguracionController`, etc.).
- `models/`: Clases de acceso a datos que interactúan con la base de datos.
- `views/`: Vistas de la aplicación estructuradas por módulos.
- `public/`: Archivos públicos o de punto de entrada (css, js, etc.).
- `sql/`: Scripts de base de datos.

## Convenciones y Reglas
1. **Idioma:** Todas las variables, funciones y nombres de archivos están principalmente en español, respetando la estructura existente.
2. **Arquitectura:** Se utiliza un patrón Modelo-Vista-Controlador (MVC) puro en PHP. No usar frameworks adicionales a menos que se solicite.
3. **Base de Datos:** Configurada a través de PDO o MySQLi, definida en el directorio `config/`.
4. **Desarrollo Frontend:** Uso de HTML, CSS y JS estándar, incorporado en los archivos `.php` de las vistas correspondientes.

Sigue estas convenciones estrictamente para mantener la cohesión del código a lo largo del desarrollo.
