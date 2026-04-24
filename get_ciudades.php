<?php
include("db.php");

if (!isset($_GET['id_pais']) || $_GET['id_pais'] == "") {
    echo "<option value=''>No hay país seleccionado</option>";
    exit();
}

$id_pais = $_GET['id_pais'];

$query = "SELECT * FROM ciudades WHERE id_pais = '$id_pais'";
$resultado = mysqli_query($conexion, $query);

if (mysqli_num_rows($resultado) > 0) {
    while($fila = mysqli_fetch_assoc($resultado)) {
        echo "<option value='".$fila['id']."'>".$fila['nombre']."</option>";
    }
} else {
    echo "<option value=''>No hay ciudades</option>";
}
?>