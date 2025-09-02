<?php // ajax/placas/listar.php

header('Content-Type: application/json');
require_once '../../config/database.php';

try {
    // Conexión a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Recibir parámetros de DataTables
    $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
    $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
    $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
    $search_value = isset($_POST['search']['value']) ? trim($_POST['search']['value']) : '';
    
    // Parámetros de filtro personalizados del formulario
    $fecha_inicio = isset($_POST['fecha_inicio']) && !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
    $fecha_fin = isset($_POST['fecha_fin']) && !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';
    $estado = isset($_POST['estado']) && !empty($_POST['estado']) ? $_POST['estado'] : '';

    
    // Columnas para ordenamiento (deben coincidir con las columnas de tu tabla)
    $columns = ['id', 'placa', 'diseno', 'cantidad', 'tamano', 'estado', 'fecha'];
    $order_column_index = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
    $order_column = isset($columns[$order_column_index]) ? $columns[$order_column_index] : 'id';
    $order_dir = isset($_POST['order'][0]['dir']) && $_POST['order'][0]['dir'] === 'asc' ? 'ASC' : 'DESC';
    
    // Construcción de la consulta base
    $base_query = "FROM placas WHERE 1=1";
    $params = [];
    
    // APLICAR FILTROS
    
    // 1. Filtro por rango de fechas
    if (!empty($fecha_inicio) && !empty($fecha_fin)) {
        $base_query .= " AND DATE(fecha) BETWEEN :fecha_inicio AND :fecha_fin";
        $params['fecha_inicio'] = $fecha_inicio;
        $params['fecha_fin'] = $fecha_fin;
    } elseif (!empty($fecha_inicio)) {
        $base_query .= " AND DATE(fecha) >= :fecha_inicio";
        $params['fecha_inicio'] = $fecha_inicio;
    } elseif (!empty($fecha_fin)) {
        $base_query .= " AND DATE(fecha) <= :fecha_fin";
        $params['fecha_fin'] = $fecha_fin;
    }
    
    // 2. Filtro por estado
    if ($estado !== '') {
        $base_query .= " AND estado = :estado";
        $params['estado'] = $estado;
    }
    
    // 5. Filtro de búsqueda general (busca en varios campos)
    if (!empty($search_value)) {
        $base_query .= " AND (
            placa LIKE :search 
            OR estado LIKE :search 
        )";
        $params['search'] = "%$search_value%";
    }
    
    // CONTAR REGISTROS TOTALES (sin filtros)
    $total_query = "SELECT COUNT(*) as total FROM placas";
    $total_stmt = $pdo->prepare($total_query);
    $total_stmt->execute();
    $total_records = $total_stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // CONTAR REGISTROS FILTRADOS
    $filtered_query = "SELECT COUNT(*) as total " . $base_query;
    $filtered_stmt = $pdo->prepare($filtered_query);
    $filtered_stmt->execute($params);
    $filtered_records = $filtered_stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // CONSULTA PRINCIPAL CON PAGINACIÓN Y ORDENAMIENTO
    $data_query = "SELECT id, placa, diseno, cantidad, tamano, estado, fecha " . 
                  $base_query . 
                  " ORDER BY $order_column $order_dir LIMIT :start, :length";
    
    $data_stmt = $pdo->prepare($data_query);
    
    // Bind de parámetros de filtros
    foreach ($params as $key => $value) {
        $data_stmt->bindValue(":$key", $value);
    }
    // Bind de parámetros de paginación
    $data_stmt->bindValue(':start', $start, PDO::PARAM_INT);
    $data_stmt->bindValue(':length', $length, PDO::PARAM_INT);
    
    $data_stmt->execute();
    $data = $data_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // FORMATEAR DATOS PARA MOSTRAR MEJOR (opcional)
    foreach ($data as &$row) {
        // Formatear el diseño con primera letra mayúscula
        $row['diseno'] = ucfirst($row['diseno']);
        
        // Formatear el tamaño (convertir guiones bajos en espacios)
        $row['tamano'] = ucfirst(str_replace('_', ' ', $row['tamano']));
        
        // Asegurar que el estado esté bien formateado
        $row['estado'] = ucfirst($row['estado']);
        
        // Formatear la fecha (opcional, se puede hacer en JavaScript también)
        if (!empty($row['fecha'])) {
            $row['fecha'] = date('Y-m-d H:i:s', strtotime($row['fecha']));
        }
    }
    
    // RESPUESTA PARA DATATABLES
    $response = [
        "draw" => $draw,
        "recordsTotal" => intval($total_records),
        "recordsFiltered" => intval($filtered_records),
        "data" => $data
    ];
    
    echo json_encode($response);

} catch (PDOException $e) {
    // Error de base de datos
    http_response_code(500);
    echo json_encode([
        "error" => "Error de conexión a base de datos: " . $e->getMessage(),
        "draw" => isset($draw) ? $draw : 1,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => []
    ]);
} catch (Exception $e) {
    // Otros errores
    http_response_code(500);
    echo json_encode([
        "error" => "Error del servidor: " . $e->getMessage(),
        "draw" => isset($draw) ? $draw : 1,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => []
    ]);
}