<?php // ajax/clientes/crear.php

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
        
        // Insertar en la base de datos

        $sql = "INSERT INTO clientes (nombre, documento, email, telefono, direccion) 
                VALUES (:nombre, :documento, :email, :telefono, :direccion)";
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':nombre' => $nombre,
            ':documento' => $documento,
            ':email' => $email,
            ':telefono' => $telefono,
            ':direccion' => $direccion
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
