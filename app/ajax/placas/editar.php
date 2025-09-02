<?php // ajax/placas/editar.php

header('Content-Type: application/json');
require_once '../../config/database.php';

try {
    $pdo = new PDO("mysql: host=$host; dbname=$dbname; charset=utf8", $username, $password);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

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

        $required_fields = ['placa', 'diseno', 'cantidad', 'tamano', 'estado'];
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

        $placa = trim($_POST['placa']);
        $diseno = trim($_POST['diseno']);
        $cantidad = trim($_POST['cantidad']);
        $tamano = trim($_POST['tamano']);
        $estado = trim($_POST['estado']);
        
        // Verificar que el registro existe

        $check_sql = "SELECT id FROM placas WHERE id = :id";
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

        $sql = "UPDATE placas 
                SET placa = :placa, diseno = :diseno, cantidad = :cantidad, tamano = :tamano, estado = :estado
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':placa' => $placa,
            ':diseno' => $diseno,
            ':cantidad' => $cantidad,
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