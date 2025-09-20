<?php // app/api/productos/crear.php

header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../middleware/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {

        // Validar campos requeridos
        $required_fields = ['producto', 'categoria', 'precio'];
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
        $producto = trim($_POST['producto']);
        $categoria = trim($_POST['categoria']);
        $precio = trim($_POST['precio']);
        $maquila = trim($_POST['maquila']);
        $mayoreo = trim($_POST['mayoreo']);
        
        // Insertar en la base de datos
        $sql = "INSERT INTO productos (producto, categoria, precio, maquila, mayoreo) 
                VALUES (:producto, :categoria, :precio, :maquila, :mayoreo)";
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':producto' => $producto,
            ':categoria' => $categoria,
            ':precio' => $precio,
            ':maquila' => $maquila,
            ':mayoreo' => $mayoreo
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
