<?php
header("Content-Type: application/json; charset=utf-8");
include('db.php');

$id_pais = intval($_GET["id_pais"] ?? 0);

if ($id_pais <= 0) {
    echo json_encode([]);
    exit;
}

$stmt = mysqli_prepare($conexion, "SELECT id, nombre FROM ciudades WHERE id_pais = ? ORDER BY nombre ASC");
mysqli_stmt_bind_param($stmt, "i", $id_pais);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$ciudades = [];
while ($row = mysqli_fetch_assoc($result)) {
    $ciudades[] = $row;
}

echo json_encode($ciudades);
mysqli_close($conexion);
?>
