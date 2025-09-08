<?php // app/modules/clientes/listar.php

header('Content-Type: application/json');
require_once '../../config/database.php';

try {

    // Construcción de la consulta

    $sql = "SELECT id, nombre, documento, email, telefono, direccion FROM clientes";

    // Preparar y ejecutar la consulta

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = [
        'data' => $clientes
    ];
    echo json_encode($response);

} catch(PDOException $e) {
    echo json_encode([
        'data' => [],
        'error' => 'Error al listar: ' . $e->getMessage()
    ]);
}