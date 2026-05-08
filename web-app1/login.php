<?php
// ============================================================
//  LOGIN.PHP - Valida credenciales contra la tabla "usuarios"
// ============================================================
include('db.php');

$username = $_POST['username'];
$password = $_POST['password'];

$query     = "SELECT * FROM usuarios WHERE username = '$username' AND password = '$password'";
$resultado = mysqli_query($conexion, $query);

if (mysqli_num_rows($resultado) > 0) {
    header("Location: index.php?success=1");
} else {
    header("Location: index.php?error=1");
}

mysqli_close($conexion);
?>
