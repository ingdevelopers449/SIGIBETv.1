<?php
require_once __DIR__ . '/config/database.php';
global $conn;

$sql = "ALTER TABLE configuracion 
        ADD COLUMN impuesto DECIMAL(5,2) DEFAULT 0 AFTER direccion,
        ADD COLUMN tipo_facturacion VARCHAR(50) DEFAULT 'Régimen Simplificado' AFTER impuesto";

if ($conn->query($sql)) {
    echo "Columnas añadidas con éxito a 'configuracion'.";
} else {
    echo "Error o las columnas ya existen: " . $conn->error;
}
