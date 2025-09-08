<?php // app/modules/dtfuvs/eliminar.php

header('Content-Type: application/json');
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    try {
        $id = intval($_POST['id']);
        
        // Verificar que el registro existe

        $check_sql = "SELECT id FROM dtfuvs WHERE id = :id";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute([':id' => $id]);
        
        if (!$check_stmt->fetch()) {
            echo json_encode([
                'success' => false,
                'message' => 'No existe'
            ]);
            exit;
        }
        
        // Eliminar el registro
        
        $sql = "DELETE FROM dtfuvs WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([':id' => $id]);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Eliminado exitosamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al eliminar'
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