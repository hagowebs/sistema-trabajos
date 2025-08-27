<?php // ajax/crear_trabajo.php

require_once '../../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
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
        
        // Insertar en la base de datos
        $sql = "INSERT INTO pruebas (titulo, descripcion, empresa, ubicacion, salario, tipo_empleo, estado) 
                VALUES (:titulo, :descripcion, :empresa, :ubicacion, :salario, :tipo_empleo, :estado)";
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':empresa' => $empresa,
            ':ubicacion' => $ubicacion,
            ':salario' => $salario,
            ':tipo_empleo' => $tipo_empleo,
            ':estado' => $estado
        ]);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Trabajo creado exitosamente',
                'id' => $pdo->lastInsertId()
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al crear el trabajo'
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