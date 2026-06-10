<?php
include('db.php');

// Verificar que se hayan enviado los campos
if (!isset($_POST['username'], $_POST['password'])) {
    header("Location: index.php?error=1");
    exit;
}

$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM usuarios WHERE username = ?";
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultado);

if ($usuario && password_verify($password, $usuario['password'])) {
    header("Location: index.php?success=1");
} else {
    header("Location: index.php?error=1");
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
exit;
?>