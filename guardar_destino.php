<?php
include('db.php');

$nombre_usuario = $_POST['nombre_usuario'];
$id_pais = $_POST['pais'];
$id_ciudad = $_POST['ciudad'];

$query = "INSERT INTO registros_viajes (nombre_usuario, id_pais, id_ciudad) 
          VALUES ('$nombre_usuario', '$id_pais', '$id_ciudad')";

if (mysqli_query($conexion, $query)) {
    header("Location: index.php?success=1&destino_guardado=1");
} else {
    header("Location: index.php?success=1&error_destino=1");
}

mysqli_close($conexion);
?>