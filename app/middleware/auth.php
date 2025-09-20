<?php // app/middleware/auth.php

session_start();

// Verifica que exista la variable y tenga el valor esperado
if (!isset($_SESSION['ingreso']) || $_SESSION['ingreso'] !== 'woolo') {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Acceso no autorizado']);
    exit;
}