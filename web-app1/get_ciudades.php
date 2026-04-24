<?php
// ============================================================
//  GET_CIUDADES.PHP
//  RÚBRICA #3 - Devuelve ciudades por id_pais en JSON
//  Llamado por fetch() desde script.js
// ============================================================
include('db.php');

$id_pais = isset($_GET['id_pais']) ? intval($_GET['id_pais']) : 0;

header('Content-Type: application/json');

if ($id_pais <= 0) {
    echo json_encode([]);
    exit;
}

$query     = "SELECT id, nombre FROM ciudades WHERE id_pais = $id_pais ORDER BY nombre";
$resultado = mysqli_query($conexion, $query);

$ciudades = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $ciudades[] = $fila;
}

echo json_encode($ciudades);
mysqli_close($conexion);
?>
