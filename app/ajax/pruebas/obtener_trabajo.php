<?php // ajax/obtener_trabajo.php

require_once '../../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    try {
        $id = intval($_POST['id']);
        
        $sql = "SELECT * FROM pruebas WHERE id = :id";
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
                'message' => 'Trabajo no encontrado'
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
?>