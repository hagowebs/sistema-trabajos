<?php // app/api/dtfs/listar.php

header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../middleware/auth.php';

try {

    // Parámetros de filtros
    $fecha_inicio = isset($_POST['fecha_inicio']) && !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
    $fecha_fin = isset($_POST['fecha_fin']) && !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';
    $estado = isset($_POST['estado']) && !empty($_POST['estado']) ? $_POST['estado'] : '';

    // Parámetro para ocultar registros 'entregados' por defecto
    $ocultar_entregados = isset($_POST['ocultar_entregados']) && $_POST['ocultar_entregados'] === 'true';

    // Construcción de la consulta
    $query = "SELECT id, dtf, diseno, tamano, estado, fecha FROM dtfs WHERE 1=1";
    $params = [];
    
    // Aplicar la regla de ocultar 'entregados' si no hay filtros activos
    if ($ocultar_entregados) {
        $query .= " AND estado != 'entregado'";
    }

    // 1. Filtro por rango de fechas
    if (!empty($fecha_inicio) && !empty($fecha_fin)) {
        $query .= " AND DATE(fecha) BETWEEN :fecha_inicio AND :fecha_fin";
        $params['fecha_inicio'] = $fecha_inicio;
        $params['fecha_fin'] = $fecha_fin;
    } elseif (!empty($fecha_inicio)) {
        $query .= " AND DATE(fecha) >= :fecha_inicio";
        $params['fecha_inicio'] = $fecha_inicio;
    } elseif (!empty($fecha_fin)) {
        $query .= " AND DATE(fecha) <= :fecha_fin";
        $params['fecha_fin'] = $fecha_fin;
    }
    
    // 2. Filtro por estado
    if ($estado !== '') {
        $query .= " AND estado = :estado";
        $params['estado'] = $estado;
    }

    // Preparar y ejecutar la consulta
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $dtfs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = [
        "data" => $dtfs
    ];
    echo json_encode($response);

} catch(PDOException $e) {
    echo json_encode([
        'data' => [],
        'error' => 'Error al listar: ' . $e->getMessage()
    ]);
}
