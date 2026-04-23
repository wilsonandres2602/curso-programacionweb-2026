
<?php
include 'conexion.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$sql = "SELECT * FROM usuarios
        WHERE username='$username'
        AND password='$password'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    header("Location: index.php?success=1");
    exit();
} else {
    header("Location: index.php?error=1");
    exit();
}
?>