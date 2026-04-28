<?php
header("Content-Type: application/json; charset=utf-8");
include('db.php');

$nombre_usuario = trim($_POST["nombre_usuario"] ?? "");
$id_pais        = intval($_POST["id_pais"]        ?? 0);
$id_ciudad      = intval($_POST["id_ciudad"]       ?? 0);

if ($nombre_usuario === "" || $id_pais <= 0 || $id_ciudad <= 0) {
    echo json_encode(["ok" => false, "error" => "Datos incompletos"]);
    exit;
}

$stmt = mysqli_prepare($conexion,
    "INSERT INTO registros_viajes (nombre_usuario, id_pais, id_ciudad) VALUES (?, ?, ?)"
);
mysqli_stmt_bind_param($stmt, "sii", $nombre_usuario, $id_pais, $id_ciudad);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["ok" => true]);
} else {
    echo json_encode(["ok" => false, "error" => mysqli_error($conexion)]);
}

mysqli_close($conexion);
?>
