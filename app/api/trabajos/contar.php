<?php // app/endpoints/trabajos/contar.php

header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../middleware/auth.php';

// Comparar usuario con id de sesión
$id_usuario = $_SESSION['id'] ?? 0;

// Construcción de la consulta
$sql = "SELECT estado, COUNT(*) as total 
        FROM trabajos 
        WHERE id_encargado = :id_usuario
        GROUP BY estado";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id_usuario' => $id_usuario]);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inicializar con 0 por si no hay registros en algún estado
$respuesta = [
    'pendiente' => 0,
    'diseño' => 0,
    'producción' => 0,
    'taller' => 0
];

// Contar trabajos por estado
foreach ($resultados as $fila) {
    $estado = strtolower($fila['estado']);
    if (isset($respuesta[$estado])) {
        $respuesta[$estado] = $fila['total'];
    }
}
echo json_encode($respuesta);