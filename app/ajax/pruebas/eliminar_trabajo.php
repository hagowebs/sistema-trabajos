<?php // ajax/eliminar_trabajo.php

require_once '../../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    try {
        $id = intval($_POST['id']);
        
        // Verificar que el trabajo existe
        $check_sql = "SELECT id FROM pruebas WHERE id = :id";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute([':id' => $id]);
        
        if (!$check_stmt->fetch()) {
            echo json_encode([
                'success' => false,
                'message' => 'El trabajo no existe'
            ]);
            exit;
        }
        
        // Eliminar el trabajo
        $sql = "DELETE FROM pruebas WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([':id' => $id]);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Trabajo eliminado exitosamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al eliminar el trabajo'
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