<?php
include('db.php');

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

// Consulta segura con prepared statement
$stmt = mysqli_prepare($conexion, "SELECT id FROM usuarios WHERE username = ? AND password = ?");
mysqli_stmt_bind_param($stmt, "ss", $username, $password);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    header("Location: index.php?success=1");
} else {
    header("Location: index.php?error=1");
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>
