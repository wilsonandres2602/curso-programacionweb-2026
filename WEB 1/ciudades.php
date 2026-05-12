<?php
include('db.php');

if (!isset($_GET['id_pais']) || empty($_GET['id_pais'])) {
    echo json_encode([]);
    exit;
}

$id_pais = (int)$_GET['id_pais'];
$query = "SELECT id, nombre FROM ciudades WHERE id_pais = ? ORDER BY nombre";
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "i", $id_pais);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$ciudades = [];
while ($row = mysqli_fetch_assoc($result)) {
    $ciudades[] = $row;
}

header('Content-Type: application/json');
echo json_encode($ciudades);
mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>