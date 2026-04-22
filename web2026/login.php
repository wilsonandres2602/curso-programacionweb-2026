<?php
session_start();
include 'conexion.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $conn->real_escape_string($_POST['username']);
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM usuarios
            WHERE username='$username'
            AND password='$password'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        $message = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Mi Proyecto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="login-wrapper">
    <div class="login-box">
        <h2>🔐 Iniciar Sesión</h2>

        <form method="POST" action="">
            <input type="text" name="username" placeholder="👤 Usuario" required>
            <input type="password" name="password" placeholder="🔑 Contraseña" required>
            <button type="submit">Entrar</button>
        </form>

        <p class="error"><?php echo htmlspecialchars($message); ?></p>
    </div>
</div>

<footer>
    <p>© <?php echo date('Y'); ?> Mi Proyecto Web &nbsp;|&nbsp;
       <a href="mailto:contacto@miproyecto.com">📧 Contacto</a>
    </p>
</footer>

</body>
</html>
