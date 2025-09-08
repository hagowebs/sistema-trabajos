<?php // app/modules/requisics/crear.php

header('Content-Type: application/json');
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {

        // Validar campos requeridos

        $required_fields = ['requisic', 'fecha_inicial', 'fecha_final', 'estado'];
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

        $requisic = trim($_POST['requisic']);
        $nota = trim($_POST['nota']);
        $fecha_inicial = trim($_POST['fecha_inicial']);
        $fecha_final = trim($_POST['fecha_final']);
        $estado = trim($_POST['estado']);
        
        // Insertar en la base de datos

        $sql = "INSERT INTO requisics (requisic, nota, fecha_inicial, fecha_final, estado) 
                VALUES (:requisic, :nota, :fecha_inicial, :fecha_final, :estado)";
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':requisic' => $requisic,
            ':nota' => $nota,
            ':fecha_inicial' => $fecha_inicial,
            ':fecha_final' => $fecha_final,
            ':estado' => $estado
        ]);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Creado exitosamente',
                'id' => $pdo->lastInsertId()
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al crear'
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