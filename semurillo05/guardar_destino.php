<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $id_pais = (int)$_POST['id_pais'];
    $id_ciudad = (int)$_POST['id_ciudad'];

    $query = "INSERT INTO registros_viajes (usuario, id_pais, id_ciudad) VALUES ('$usuario', $id_pais, $id_ciudad)";

    if (mysqli_query($conexion, $query)) {
        // Redirigir de vuelta al dashboard con un mensaje de éxito
        header("Location: index.php?success=1");
        exit();
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>
