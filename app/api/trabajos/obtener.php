<?php // app/api/trabajos/obtener.php

header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../middleware/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    try {
        $id = intval($_POST['id']);
        $sql = "SELECT t.*, u.nombre AS nombre_vendedor
                FROM trabajos t
                LEFT JOIN usuarios u ON t.id_vendedor = u.id
                WHERE t.id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $trabajo = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($trabajo) {
            echo json_encode([
                'success' => true,
                'data' => $trabajo
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
