<?php
include("db.php");

$id_pais = $_GET['id_pais'];

$result = mysqli_query($conexion, "SELECT * FROM ciudades WHERE id_pais = $id_pais");

$ciudades = [];

while($row = mysqli_fetch_assoc($result)){
  $ciudades[] = $row;
}

echo json_encode($ciudades);
?>