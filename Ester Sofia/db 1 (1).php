<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "mi_proyecto";
 
$conexion = mysqli_connect($host, $user, $pass, $db);
 
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>