<?php // app/modules/trabajos/crear.php

header('Content-Type: application/json');
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {

        // Validar campos requeridos

        $required_fields = ['id_cliente','id_vendedor','id_encargado','categoria','trabajo', 'fecha_inicial','fecha_final',
        'envio','estado','metodo_pago'];
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

        $id_cliente = trim($_POST['id_cliente']);
        $id_vendedor = trim($_POST['id_vendedor']);
        $id_encargado = trim($_POST['id_encargado']);
        $categoria = trim($_POST['categoria']);
        $trabajo = trim($_POST['trabajo']);
        $fecha_inicial = trim($_POST['fecha_inicial']);
        $fecha_final = trim($_POST['fecha_final']);
        $envio = trim($_POST['envio']);
        $estado = trim($_POST['estado']);
        $precio = trim($_POST['precio']);
        $anticipo = trim($_POST['anticipo']);
        $restante = trim($_POST['restante']);
        $metodo_pago = trim($_POST['metodo_pago']);
        $anticipo2 = trim($_POST['anticipo2']);
        $restante2 = trim($_POST['restante2']);
        $metodo_pago2 = trim($_POST['metodo_pago2']);
        $fecha2 = trim($_POST['fecha2']);
        $anticipo3 = trim($_POST['anticipo3']);
        $restante3 = trim($_POST['restante3']);
        $metodo_pago3 = trim($_POST['metodo_pago3']);
        $fecha3 = trim($_POST['fecha3']);

        // Valores numéricos con fallback a 0
        $anticipo2  = isset($_POST['anticipo2'])  && $_POST['anticipo2']  !== '' ? $_POST['anticipo2']  : 0;
        $restante2  = isset($_POST['restante2'])  && $_POST['restante2']  !== '' ? $_POST['restante2']  : 0;
        $anticipo3  = isset($_POST['anticipo3'])  && $_POST['anticipo3']  !== '' ? $_POST['anticipo3']  : 0;
        $restante3  = isset($_POST['restante3'])  && $_POST['restante3']  !== '' ? $_POST['restante3']  : 0;

        // Valores opcionales (NULL si no vienen)
        $metodo_pago2 = !empty($_POST['metodo_pago2']) ? $_POST['metodo_pago2'] : null;
        $fecha2       = !empty($_POST['fecha2'])       ? $_POST['fecha2']       : null;
        $metodo_pago3 = !empty($_POST['metodo_pago3']) ? $_POST['metodo_pago3'] : null;
        $fecha3       = !empty($_POST['fecha3'])       ? $_POST['fecha3']       : null;
        
        // Insertar en la base de datos

        $sql = "INSERT INTO trabajos (id_cliente, id_vendedor, id_encargado, categoria, trabajo, fecha_inicial, fecha_final,
                envio, estado, precio, anticipo, restante, metodo_pago, anticipo2, restante2, metodo_pago2, fecha2,
                anticipo3, restante3, metodo_pago3, fecha3) 
                VALUES (:id_cliente, :id_vendedor, :id_encargado, :categoria, :trabajo, :fecha_inicial, :fecha_final,
                :envio, :estado, :precio, :anticipo, :restante, :metodo_pago, :anticipo2, :restante2, :metodo_pago2, :fecha2,
                :anticipo3, :restante3, :metodo_pago3, :fecha3)";
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':id_cliente' => $id_cliente,
            ':id_vendedor' => $id_vendedor,
            ':id_encargado' => $id_encargado,
            ':categoria' => $categoria,
            ':trabajo' => $trabajo,
            ':fecha_inicial' => $fecha_inicial,
            ':fecha_final' => $fecha_final,
            ':envio' => $envio,
            ':estado' => $estado,
            ':precio' => $precio,
            ':anticipo' => $anticipo,
            ':restante' => $restante,
            ':metodo_pago' => $metodo_pago,
            ':anticipo2' => $anticipo2,
            ':restante2' => $restante2,
            ':metodo_pago2' => $metodo_pago2,
            ':fecha2' => $fecha2,
            ':anticipo3' => $anticipo3,
            ':restante3' => $restante3,
            ':metodo_pago3' => $metodo_pago3,
            ':fecha3' => $fecha3
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