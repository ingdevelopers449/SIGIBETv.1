<?php
require_once __DIR__ . '/config/database.php';
global $conn;

$sql = "ALTER TABLE clientes ADD COLUMN observaciones TEXT NULL AFTER direccion";
if ($conn->query($sql)) {
    echo "Columna 'observaciones' agregada con éxito.";
} else {
    echo "Error o ya existe: " . $conn->error;
}
