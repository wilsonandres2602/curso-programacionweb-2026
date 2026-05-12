<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>

<h1>Bienvenido <?php echo $_SESSION['username']; ?> 🎉</h1>

<p>Login correcto. Proyecto funcionando.</p>

<a href="logout.php">Cerrar sesión</a>

</body>
</html>