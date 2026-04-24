<?php
// get_ciudades.php – Devuelve las ciudades de un país dado (?id_pais=N)
header('Content-Type: application/json');
require_once 'db.php';

$id_pais = isset($_GET['id_pais']) ? (int)$_GET['id_pais'] : 0;

if ($id_pais <= 0) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("SELECT id, nombre FROM ciudades WHERE id_pais = ? ORDER BY nombre ASC");
$stmt->bind_param('i', $id_pais);
$stmt->execute();
$result = $stmt->get_result();

$ciudades = [];
while ($row = $result->fetch_assoc()) {
    $ciudades[] = $row;
}

echo json_encode($ciudades);
$stmt->close();
$conn->close();
