<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "my_proyecto";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conexion = mysqli_connect($host, $username, $password, $dbname);
mysqli_set_charset($conexion, "utf8mb4");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>