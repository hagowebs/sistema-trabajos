<?php // app/modules/productos/listar.php

header('Content-Type: application/json');
require_once '../../config/database.php';

try {

    // Construcción de la consulta

    $sql = "SELECT id, producto, categoria, precio, maquila, mayoreo FROM productos";

    // Preparar y ejecutar la consulta

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = [
        'data' => $productos
    ];
    echo json_encode($response);

} catch(PDOException $e) {
    echo json_encode([
        'data' => [],
        'error' => 'Error al listar: ' . $e->getMessage()
    ]);
}