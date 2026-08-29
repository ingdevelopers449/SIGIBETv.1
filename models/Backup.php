<?php
require_once __DIR__ . '/../config/database.php';

/**
 * MODELO DE BACKUP
 * 
 * Se encarga de hacer el puente con mysqldump y mysql para generar y restaurar backups.
 */
class Backup
{
    private $dbHost = 'localhost';
    private $dbUser = 'root';
    private $dbPass = '';
    private $dbName = 'sigibet_db';

    public function generarBackup(string $rutaDestino): bool
    {
        // En Laragon/XAMPP, mysqldump normalmente no pide password si es root sin clave
        $auth = empty($this->dbPass) ? "-u {$this->dbUser}" : "-u {$this->dbUser} -p{$this->dbPass}";
        $cmd = "mysqldump {$auth} -h {$this->dbHost} {$this->dbName} > \"{$rutaDestino}\"";
        
        $output = [];
        $return_var = 0;
        
        exec($cmd . ' 2>&1', $output, $return_var);
        
        return $return_var === 0;
    }

    public function restaurarBackup(string $rutaArchivo): array
    {
        if (!file_exists($rutaArchivo)) {
            return ['success' => false, 'mensaje' => 'El archivo de respaldo no existe o no se pudo cargar.'];
        }

        $auth = empty($this->dbPass) ? "-u {$this->dbUser}" : "-u {$this->dbUser} -p{$this->dbPass}";
        $cmd = "mysql {$auth} -h {$this->dbHost} {$this->dbName} < \"{$rutaArchivo}\"";
        
        $output = [];
        $return_var = 0;
        
        exec($cmd . ' 2>&1', $output, $return_var);
        
        if ($return_var === 0) {
            return ['success' => true, 'mensaje' => 'Base de datos restaurada correctamente.'];
        } else {
            return ['success' => false, 'mensaje' => 'Error al restaurar: ' . implode("\n", $output)];
        }
    }
}
