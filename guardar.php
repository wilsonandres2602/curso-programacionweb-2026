<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario = $_POST['usuario'] ?? '';
    $pais    = $_POST['pais']    ?? '';
    $ciudad  = $_POST['ciudad']  ?? '';

    if (empty($usuario) || empty($pais) || empty($ciudad)) {
        header("Location: registro.php?guardado=error");
        exit;
    }

    // PUNTO 3: Guardar en registros_viajes
    $stmt = $conn->prepare("INSERT INTO registros_viajes (usuario, id_pais, id_ciudad) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $usuario, $pais, $ciudad);

    if ($stmt->execute()) {
        header("Location: registro.php?guardado=ok");
    } else {
        header("Location: registro.php?guardado=error");
    }

    $stmt->close();
    $conn->close();

} else {
    header("Location: index.php");
}
?>