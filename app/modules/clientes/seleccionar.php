<?php // app/modules/clientes/seleccionar.php

header('Content-Type: application/json');
require_once '../../config/database.php';

try {

    // Construcción de la consulta

    $stmt = $pdo->query("SELECT id, nombre FROM clientes ORDER BY nombre ASC");

    // Preparar y ejecutar la consulta

    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $clientes]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
