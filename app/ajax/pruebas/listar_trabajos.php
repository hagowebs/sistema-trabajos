<?php // ajax/listar_trabajos.php

require_once '../../config/database.php';

header('Content-Type: application/json');

try {
    $sql = "SELECT id, titulo, descripcion, empresa, ubicacion, salario, tipo_empleo, estado, fecha_publicacion 
            FROM pruebas 
            ORDER BY fecha_creacion DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $trabajos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formato requerido por DataTables
    $response = [
        'data' => $trabajos
    ];
    
    echo json_encode($response);
    
} catch(PDOException $e) {
    echo json_encode([
        'data' => [],
        'error' => 'Error al obtener los trabajos: ' . $e->getMessage()
    ]);
}
?>