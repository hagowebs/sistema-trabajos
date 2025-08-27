<?php // ajax/editar_trabajo.php

require_once '../../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Verificar que se proporcione el ID
        if (empty($_POST['id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'ID del trabajo es requerido'
            ]);
            exit;
        }
        
        $id = intval($_POST['id']);
        
        // Validar campos requeridos
        $required_fields = ['titulo', 'descripcion', 'empresa', 'ubicacion', 'tipo_empleo'];
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
        $titulo = trim($_POST['titulo']);
        $descripcion = trim($_POST['descripcion']);
        $empresa = trim($_POST['empresa']);
        $ubicacion = trim($_POST['ubicacion']);
        $tipo_empleo = $_POST['tipo_empleo'];
        $salario = !empty($_POST['salario']) ? floatval($_POST['salario']) : null;
        $estado = isset($_POST['estado']) ? $_POST['estado'] : 'Activo';
        
        // Validar tipo de empleo
        $tipos_validos = ['Tiempo Completo', 'Tiempo Parcial', 'Freelance', 'Remoto'];
        if (!in_array($tipo_empleo, $tipos_validos)) {
            echo json_encode([
                'success' => false,
                'message' => 'Tipo de empleo no válido'
            ]);
            exit;
        }
        
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
        
        // Actualizar en la base de datos
        $sql = "UPDATE pruebas 
                SET titulo = :titulo, descripcion = :descripcion, empresa = :empresa, 
                    ubicacion = :ubicacion, salario = :salario, tipo_empleo = :tipo_empleo, 
                    estado = :estado, fecha_actualizacion = NOW()
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':empresa' => $empresa,
            ':ubicacion' => $ubicacion,
            ':salario' => $salario,
            ':tipo_empleo' => $tipo_empleo,
            ':estado' => $estado,
            ':id' => $id
        ]);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Trabajo actualizado exitosamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al actualizar el trabajo'
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
?>