<?php // app/api/usuarios/crear.php

header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../middleware/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {

        // Validar campos requeridos
        $required_fields = ['nombre', 'usuario', 'clave', 'perfil'];
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
        $usuario = trim($_POST['usuario']);
        $clave = trim($_POST['clave']);
        $clave = crypt($clave, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
        $perfil = trim($_POST['perfil']);

        // Verificar si ya existe el usuario
        $check = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = :usuario LIMIT 1");
        $check->execute([':usuario' => $usuario]);
        if ($check->fetch()) {
            echo json_encode([
                'success' => false,
                'message' => 'El usuario ya está registrado'
            ]);
            exit;
        }
        
        // Insertar en la base de datos
        $sql = "INSERT INTO usuarios (nombre, usuario, clave, perfil) 
                VALUES (:nombre, :usuario, :clave, :perfil)";
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':nombre' => $nombre,
            ':usuario' => $usuario,
            ':clave' => $clave,
            ':perfil' => $perfil
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
