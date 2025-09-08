<?php // app/modules/clientes/editar.php

header('Content-Type: application/json');
require_once '../../config/database.php';

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

        $required_fields = ['nombre', 'telefono'];
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

        $nombre = trim($_POST['nombre']);
        $documento = trim($_POST['documento']);
        $email = trim($_POST['email']);
        $telefono = trim($_POST['telefono']);
        $direccion = trim($_POST['direccion']);
        
        // Verificar que el registro existe

        $check_sql = "SELECT id FROM clientes WHERE id = :id";
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

        $sql = "UPDATE clientes 
                SET nombre = :nombre, documento = :documento, email = :email, telefono = :telefono, direccion = :direccion
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':nombre' => $nombre,
            ':documento' => $documento,
            ':email' => $email,
            ':telefono' => $telefono,
            ':direccion' => $direccion,
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