<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario = $_POST['usuario'] ?? '';
    $pais    = $_POST['pais'] ?? '';
    $ciudad  = $_POST['ciudad'] ?? '';

    if (empty($usuario) || empty($pais) || empty($ciudad)) {
        header("Location: registro.php?guardado=error");
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO registros_viajes (nombre_usuario, id_pais, id_ciudad) VALUES (?, ?, ?)");

    if (!$stmt) {
        die("Error en prepare: " . $conn->error);
    }

    $stmt->bind_param("sii", $usuario, $pais, $ciudad);

    if ($stmt->execute()) {
        header("Location: registro.php?guardado=ok");
        exit;
    } else {
        die("Error al guardar: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

} else {
    header("Location: index.php");
    exit;
}
?>