<?php
/**
 * Conexión centralizada a la base de datos MySQL
 * Proyecto: No Más Unos
 *
 * Permite conectarse a MySQL tanto en entornos locales (XAMPP, WAMP, Laragon, Phoenix Code)
 * como en entornos remotos o servidores mediante variables de entorno.
 */

// Desactiva el modo de reporte por excepciones automáticas de mysqli en PHP 8.1+
// para permitir un manejo controlado y amigable de los errores de conexión.
if (function_exists('mysqli_report')) {
    mysqli_report(MYSQLI_REPORT_OFF);
}

// Parámetros de conexión configurables:
// 1. Si existen variables de entorno (DB_HOST, DB_USER, etc.), se usan prioritariamente.
// 2. Si no existen, se usan los valores por defecto del entorno local típico (XAMPP / Wamp).
$servidor    = getenv('DB_HOST') !== false ? getenv('DB_HOST') : 'localhost';
$usuario     = getenv('DB_USER') !== false ? getenv('DB_USER') : 'root';
$clave       = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$baseDeDatos = getenv('DB_NAME') !== false ? getenv('DB_NAME') : 'nomasunos';
$puerto      = getenv('DB_PORT') !== false ? (int)getenv('DB_PORT') : 3306;

// Intento de conexión con supresión de warnings directos para capturar el error limpiamente
$conexion = @mysqli_connect($servidor, $usuario, $clave, $baseDeDatos, $puerto);

// Variables estándar para compatibilidad con todo el proyecto
$con    = $conexion; // Utilizado en los archivos api_*.php
$enlace = $conexion; // Utilizado en login.php y registro.php

$error_conexion = null;

if (!$conexion) {
    $error_conexion = mysqli_connect_error();
} else {
    // Configura el juego de caracteres a utf8mb4 para admitir tildes, ñ y caracteres especiales
    mysqli_set_charset($conexion, 'utf8mb4');
}

/**
 * Retorna si la conexión se encuentra actualmente activa
 * @param mysqli|null $link
 * @return bool
 */
function verificarConexion($link = null) {
    global $conexion;
    $target = $link ?: $conexion;
    return ($target instanceof mysqli) && @mysqli_ping($target);
}
