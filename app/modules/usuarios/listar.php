<?php // app/modules/usuarios/listar.php

header('Content-Type: application/json');
require_once '../../config/database.php';

try {

    // Construcción de la consulta

    $sql = "SELECT id, nombre, usuario, clave, perfil, estado, ultimo_login FROM usuarios";

    // Preparar y ejecutar la consulta

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = [
        'data' => $usuarios
    ];
    echo json_encode($response);

} catch(PDOException $e) {
    echo json_encode([
        'data' => [],
        'error' => 'Error al listar: ' . $e->getMessage()
    ]);
}