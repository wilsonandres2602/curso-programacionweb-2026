<?php
include('db.php');

// Verificar que se hayan enviado los campos
if (!isset($_POST['username'], $_POST['password'])) {
    header("Location: index.php?error=1");
    exit;
}

$username = $_POST['username'];
$password = $_POST['password'];

// Consulta preparada para evitar inyección SQL
$query = "SELECT * FROM usuarios WHERE username = ? AND password = ?";
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "ss", $username, $password);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($resultado) > 0) {
    // Credenciales correctas → redirigir con éxito
    header("Location: index.php?success=1");
} else {
    // Credenciales incorrectas
    header("Location: index.php?error=1");
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
exit;
?>