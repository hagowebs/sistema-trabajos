<?php // app/api/cxc/listar.php

header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../middleware/auth.php';

try {

    // Parámetros de filtros
    $fecha_inicio = isset($_POST['fecha_inicio']) && !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
    $fecha_fin = isset($_POST['fecha_fin']) && !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';
    $estado = isset($_POST['estado']) && !empty($_POST['estado']) ? $_POST['estado'] : '';
    $cliente = isset($_POST['cliente']) && !empty($_POST['cliente']) ? $_POST['cliente'] : '';

    // Parámetro para ocultar registros 'entregados' por defecto
    $ocultar_entregados = isset($_POST['ocultar_entregados']) && $_POST['ocultar_entregados'] === 'true';

    // Construcción de la consulta
    $query = "SELECT t.id,
                     t.id_cliente,
                     COALESCE(c.nombre, '') AS cliente,
                     COALESCE(c.telefono, '') AS telefono,
                     t.trabajo,
                     t.precio,
                     t.anticipo,
                     t.fecha_inicial,
                     t.metodo_pago,
                     t.anticipo2,
                     t.fecha2,
                     t.metodo_pago2,
                     t.anticipo3,
                     t.fecha3,
                     t.metodo_pago3,
                     t.estado
              FROM trabajos t
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

    // 3. Filtro por cliente
    if ($cliente !== '') {
        $query .= " AND t.id_cliente = :cliente";
        $params['cliente'] = $cliente;
    }

    // Preparar y ejecutar la consulta
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $trabajos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Procesar resultados y calcular columna "cuenta"
    $data = [];
    foreach ($trabajos as $row) {

        // Si el id es NULL o 0, ignorar fila
        if (empty($row['id']) || $row['id'] == 0) {
            continue;
        }

        // Calcular cuenta
        $anticipo  = floatval($row['anticipo']);
        $anticipo2 = floatval($row['anticipo2']);
        $anticipo3 = floatval($row['anticipo3']);
        $sumapagos = $anticipo + $anticipo2 + $anticipo3;
        $precio    = floatval($row['precio']);
        if ($precio == 0) {
            $cuentaCalc = "Pendiente";
        } elseif ($precio != 0 && $anticipo == 0 && $anticipo2 == 0 && $anticipo3 == 0) {
            $cuentaCalc = "Sin pago";
        } elseif (abs($precio - $sumapagos) < 0.0001) {
            $cuentaCalc = "OK";
        } elseif ($precio != 0 && $precio <= $sumapagos) {
            $cuentaCalc = "Revisar";
        } elseif ($precio != 0) {
            $cuentaCalc = "Abonado";
        } else {
            $cuentaCalc = "Pendiente";
        }
        $row['cuenta'] = $cuentaCalc;
        $data[] = $row;
        
    }
    echo json_encode(["data" => $data], JSON_UNESCAPED_UNICODE);

} catch(PDOException $e) {
    echo json_encode([
        'data' => [],
        'error' => 'Error al listar: ' . $e->getMessage()
    ]);
}
