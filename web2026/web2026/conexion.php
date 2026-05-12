<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "curso_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>