<?php // app/api/trabajos/listar.php

header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../middleware/auth.php';

try {

    // Parámetros de filtros
    $fecha_inicio = isset($_POST['fecha_inicio']) && !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
    $fecha_fin = isset($_POST['fecha_fin']) && !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';
    $estado = isset($_POST['estado']) && !empty($_POST['estado']) ? $_POST['estado'] : '';
    $encargado = isset($_POST['encargado']) && !empty($_POST['encargado']) ? $_POST['encargado'] : '';

    // Parámetro para ocultar registros 'entregados' por defecto
    $ocultar_entregados = isset($_POST['ocultar_entregados']) && $_POST['ocultar_entregados'] === 'true';

    // Construcción de la consulta
    $query = "SELECT t.id,
                   t.id_encargado,
                   t.id_cliente,
                   COALESCE(u.nombre, '') AS encargado,
                   COALESCE(c.nombre, '') AS cliente,
                   COALESCE(c.telefono, '') AS telefono,
                   t.trabajo,
                   t.fecha_inicial,
                   t.fecha_final,
                   t.estado
            FROM trabajos t
            LEFT JOIN usuarios u ON t.id_encargado = u.id
            LEFT JOIN clientes c ON t.id_cliente = c.id
            WHERE 1=1";
    $params = [];
    
    // Aplicar la regla de ocultar 'entregados' si no hay filtros activos
    if ($ocultar_entregados) {
        $query .= " AND LOWER(t.estado) != 'Archivado'";
    }

    // 1. Filtro por rango de fechas
    if (!empty($fecha_inicio) && !empty($fecha_fin)) {
        $query .= " AND DATE(t.fecha_inicial) BETWEEN :fecha_inicio AND :fecha_fin";
        $params['fecha_inicio'] = $fecha_inicio;
        $params['fecha_fin'] = $fecha_fin;
    } elseif (!empty($fecha_inicio)) {
        $query .= " AND DATE(t.fecha_inicial) >= :fecha_inicio";
        $params['fecha_inicio'] = $fecha_inicio;
    } elseif (!empty($fecha_fin)) {
        $query .= " AND DATE(t.fecha_inicial) <= :fecha_fin";
        $params['fecha_fin'] = $fecha_fin;
    }
    
    // 2. Filtro por estado
    if ($estado !== '') {
        $query .= " AND t.estado = :estado";
        $params['estado'] = $estado;
    }

    // 3. Filtro por encargado para usuarios admin
    if ($_SESSION['perfil'] == 'Administrador' || $_SESSION['perfil'] == 'Especial') {
        if ($encargado !== '') {
            $query .= " AND t.id_encargado = :encargado";
            $params['encargado'] = $encargado;
        }
    } else {
        $query .= " AND t.id_encargado = :encargado";
        $params['encargado'] = $_SESSION['id']; 
    }

    // Preparar y ejecutar la consulta
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $trabajos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = [
        "data" => $trabajos
    ];
    echo json_encode($response);

} catch(PDOException $e) {
    echo json_encode([
        'data' => [],
        'error' => 'Error al listar: ' . $e->getMessage()
    ]);
}
