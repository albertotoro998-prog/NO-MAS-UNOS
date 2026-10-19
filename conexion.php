<?php
// Conexión a la base de datos "nomasunos" (XAMPP local)
$host = "localhost";
$db   = "nomasunos";
$user = "root";
$pass = "";

// Conexión PDO: variable $pdo
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Conexión mysqli: variable $conexion (por si algún archivo la usa)
$conexion = new mysqli($host, $user, $pass, $db);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
$conexion->set_charset("utf8mb4");

// Alias para los archivos que usan $enlace
$enlace = $conexion;
