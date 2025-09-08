<?php // app/modules/usuarios/seleccionar.php

header('Content-Type: application/json');
require_once '../../config/database.php';

try {

    // Construcción de la consulta

    $stmt = $pdo->query("SELECT id, nombre FROM usuarios ORDER BY nombre ASC");

    // Preparar y ejecutar la consulta

    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $usuarios]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}