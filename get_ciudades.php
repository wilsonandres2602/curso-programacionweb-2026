<?php
include('db.php');

if (isset($_GET['id_pais'])) {
    $id_pais = $_GET['id_pais'];

    echo '<option value="">Selecciona una ciudad</option>';

    $query = "SELECT id, nombre FROM ciudades WHERE id_pais = $id_pais";
    $resultado = mysqli_query($conexion, $query);

    if ($resultado) {
        while ($row = mysqli_fetch_assoc($resultado)) {
            echo '<option value="' . $row['id'] . '">' . $row['nombre'] . '</option>';
        }
    }

    mysqli_close($conexion);
}
?>