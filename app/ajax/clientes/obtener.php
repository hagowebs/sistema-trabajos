<?php // ajax/clientes/obtener.php

header('Content-Type: application/json');
require_once '../../config/database.php';

try {
    $pdo = new PDO("mysql: host=$host; dbname=$dbname; charset=utf8", $username, $password);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    try {
        $id = intval($_POST['id']);
        $sql = "SELECT * FROM clientes WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($cliente) {
            echo json_encode([
                'success' => true,
                'data' => $cliente
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No encontrado'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error de base de datos: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'ID no proporcionado'
    ]);
}