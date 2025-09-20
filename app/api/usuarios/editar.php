<?php // app/api/usuarios/editar.php

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
        
        // Validar campos requeridos (excepto clave que puede ser vacía si no se cambia)
        $required_fields = ['nombre', 'usuario', 'perfil'];
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
        $perfil = trim($_POST['perfil']);
        
        // Verificar que el registro existe
        $check_sql = "SELECT id FROM usuarios WHERE id = :id";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute([':id' => $id]);
        if (!$check_stmt->fetch()) {
            echo json_encode([
                'success' => false,
                'message' => 'El registro no existe'
            ]);
            exit;
        }
        
        // Construir la consulta dinámicamente según si hay nueva clave
        if (!empty($_POST['clave'])) {

            // Encriptar solo si se cambia
            $clave = crypt(trim($_POST['clave']), '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
            $sql = "UPDATE usuarios 
                    SET nombre = :nombre, usuario = :usuario, clave = :clave, perfil = :perfil
                    WHERE id = :id";
            $params = [
                ':nombre' => $nombre,
                ':usuario' => $usuario,
                ':clave' => $clave,
                ':perfil' => $perfil,
                ':id' => $id
            ];

        } else {

            // Mantener clave anterior
            $sql = "UPDATE usuarios 
                    SET nombre = :nombre, usuario = :usuario, perfil = :perfil
                    WHERE id = :id";
            $params = [
                ':nombre' => $nombre,
                ':usuario' => $usuario,
                ':perfil' => $perfil,
                ':id' => $id
            ];

        }

        // Ejecutar update
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute($params);
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
