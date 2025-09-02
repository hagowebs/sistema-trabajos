<?php // ajax/usuarios/listar.php

header('Content-Type: application/json');
require_once '../../config/database.php';

try {
    $pdo = new PDO("mysql: host=$host; dbname=$dbname; charset=utf8", $username, $password);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

try {
    $sql = "SELECT * FROM usuarios";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = [
        'data' => $usuarios
    ];
    echo json_encode($response);
} catch(PDOException $e) {
    echo json_encode([
        'data' => [],
        'error' => 'Error al listar: ' . $e->getMessage()
    ]);
}