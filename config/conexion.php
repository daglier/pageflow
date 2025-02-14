<?php
// Definición de constantes para los detalles de la conexión a la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'pageflow_db');

// Definición de constantes para los nombres de las tablas
define('TABLE_LIBROS', 'libros');
define('TABLE_PRESTAMOS', 'prestamos');
define('TABLE_PENALIZACIONES', 'penalizaciones');
define('TABLE_USUARIOS', 'usuarios');

// Creación de una nueva instancia de la clase mysqli para establecer la conexión a la base de datos
$conexion = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Verificación de errores en la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>