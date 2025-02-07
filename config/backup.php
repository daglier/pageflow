<?php
require_once "conexion.php";

define('MYSQLDUMP_COMMAND', "C:\\xampp\\mysql\\bin\\mysqldump --user=" . DB_USER . " --password=" . DB_PASSWORD . " --host=" . DB_HOST . " " . DB_NAME);

// Carpeta donde se guardarán los respaldos
$backupDir = __DIR__ . '/../backups/'; 
if (!file_exists($backupDir)) {
    mkdir($backupDir, 0777, true); // Crear la carpeta si no existe
}

$fechaHoy = date("Y-m-d"); // Obtener la fecha de hoy

$backupFile = $backupDir . "respaldo_$fechaHoy.sql"; // Nombre del archivo de respaldo

// Verificar si ya se hizo un respaldo hoy
if (file_exists($backupFile)) {
    return; // Si ya existe, no hace el respaldo nuevamente
}

// Comando para generar el respaldo de la base de datos
$command = MYSQLDUMP_COMMAND . " > $backupFile";

// Ejecutar el comando
exec($command, $output, $returnVar);
?>