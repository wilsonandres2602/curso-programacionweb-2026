<?php
include('db.php');

$nombre    = mysqli_real_escape_string($conexion, $_POST['nombre_usuario']);
$id_pais   = (int) $_POST['id_pais'];
$id_ciudad = (int) $_POST['id_ciudad'];

$query = "INSERT INTO destinos (nombre_usuario, id_pais, id_ciudad) VALUES ('$nombre', $id_pais, $id_ciudad)";
$resultado = mysqli_query($conexion, $query);

if ($resultado) {
    header("Location: index.php?success=1&guardado=1");
} else {
    header("Location: index.php?success=1&error_destino=1");
}

mysqli_close($conexion);
?>