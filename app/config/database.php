<?php // app/database

$host = 'localhost';
$dbname = 'sistema';
$username = 'root';
$password = '';

// Conexión a la base de datos
try {
    $pdo = new PDO("mysql: host=$host; dbname=$dbname; charset=utf8", $username, $password);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}