<?php
include('db.php');

$username = $_POST['username'];
$password = $_POST['password'];

// Buscamos en la base de datos
$query = "SELECT * FROM usuarios WHERE username = '$username' AND password = '$password'";
$resultado = mysqli_query($conexion, $query);

if (mysqli_num_rows($resultado) > 0) {
    // Si los datos existen, vamos al index con éxito
    header("Location: index.php?success=1");
} else {
    // Si no, regresamos con error
    header("Location: index.php?error=1");
}

mysqli_close($conexion);
?>