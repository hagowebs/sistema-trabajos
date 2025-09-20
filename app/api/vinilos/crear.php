<?php // app/api/vinilos/crear.php

header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../middleware/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {

        // Validar campos requeridos
        $required_fields = ['vinilo', 'tamano', 'estado'];
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
        $vinilo = trim($_POST['vinilo']);
        $diseno = trim($_POST['diseno']);
        $tamano = trim($_POST['tamano']);
        $estado = trim($_POST['estado']);
        
        // Insertar en la base de datos
        $sql = "INSERT INTO vinilos (vinilo, diseno, tamano, estado) 
                VALUES (:vinilo, :diseno, :tamano, :estado)";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':vinilo' => $vinilo,
            ':diseno' => $diseno,
            ':tamano' => $tamano,
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
