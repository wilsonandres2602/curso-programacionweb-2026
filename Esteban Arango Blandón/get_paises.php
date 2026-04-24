<?php
// get_paises.php – Devuelve todos los países en JSON
header('Content-Type: application/json');
require_once 'db.php';

$result = $conn->query("SELECT id, nombre FROM paises ORDER BY nombre ASC");

$paises = [];
while ($row = $result->fetch_assoc()) {
    $paises[] = $row;
}

echo json_encode($paises);
$conn->close();
