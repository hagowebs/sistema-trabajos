<?php // Base de datos

$host = 'localhost';
$dbname = 'sistema';
$username = 'emilio';
$password = 'cadava';

// Conexión

try {
    $pdo = new PDO("mysql: host=$host; dbname=$dbname; charset=utf8", $username, $password);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}