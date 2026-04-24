<?php
// guardar_destino.php – Inserta un registro en registros_viajes (POST)
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Leer y sanear datos
$nombre   = trim($_POST['nombre']   ?? '');
$id_pais  = (int)($_POST['id_pais']  ?? 0);
$id_ciudad= (int)($_POST['id_ciudad']?? 0);

if ($nombre === '' || $id_pais <= 0 || $id_ciudad <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos o inválidos']);
    exit;
}

$stmt = $conn->prepare(
    "INSERT INTO registros_viajes (nombre_usuario, id_pais, id_ciudad) VALUES (?, ?, ?)"
);
$stmt->bind_param('sii', $nombre, $id_pais, $id_ciudad);

if ($stmt->execute()) {
    echo json_encode(['ok' => true, 'id' => $conn->insert_id]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al guardar: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
