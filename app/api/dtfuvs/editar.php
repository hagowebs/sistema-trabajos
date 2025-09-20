<?php // app/api/dtfuvs/editar.php

header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../middleware/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {

        // Verificar que se proporcione el ID
        if (empty($_POST['id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'ID es requerido'
            ]);
            exit;
        }
        $id = intval($_POST['id']);
        
        // Validar campos requeridos
        $required_fields = ['dtfuv', 'tamano', 'estado'];
        $missing_fields = [];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $missing_fields[] = $field;
            }
        }
        if (!empty($missing_fields)) {
            echo json_encode([
                'success' => false,
                'message' => 'Campos requeridos faltantes: ' . implode(', ', $missing_fields)
            ]);
            exit;
        }
        
        // Limpiar y validar datos
        $dtfuv = trim($_POST['dtfuv']);
        $diseno = trim($_POST['diseno']);
        $tamano = trim($_POST['tamano']);
        $estado = trim($_POST['estado']);
        
        // Verificar que el registro existe
        $check_sql = "SELECT id FROM dtfuvs WHERE id = :id";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute([':id' => $id]);
        if (!$check_stmt->fetch()) {
            echo json_encode([
                'success' => false,
                'message' => 'El registro no existe'
            ]);
            exit;
        }
        
        // Actualizar en la base de datos
        $sql = "UPDATE dtfuvs 
                SET dtfuv = :dtfuv, diseno = :diseno, tamano = :tamano, estado = :estado
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':dtfuv' => $dtfuv,
            ':diseno' => $diseno,
            ':tamano' => $tamano,
            ':estado' => $estado,
            ':id' => $id
        ]);

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Actualizado exitosamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al actualizar'
            ]);
        }
        
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error de base de datos: ' . $e->getMessage()
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error general: ' . $e->getMessage()
        ]);
    }

} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
}
