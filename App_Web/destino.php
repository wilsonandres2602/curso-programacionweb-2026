<?php
include("db.php");

$nombre = $_POST['nombre_usuario'];
$pais = $_POST['id_pais'];
$ciudad = $_POST['id_ciudad'];

mysqli_query($conexion, "INSERT INTO registros_viajes(nombre_usuario,id_pais,id_ciudad) 
VALUES ('$nombre','$pais','$ciudad')");

echo json_encode(["message" => "Guardado correctamente"]);
?>