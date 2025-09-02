<?php // ajax/usuarios/obtener.php

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
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuario) {
            echo json_encode([
                'success' => true,
                'data' => $usuario
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