<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$nombre_usuario = $_POST['nombre_usuario'] ?? '';
$id_pais = $_POST['id_pais'] ?? 0;
$id_ciudad = $_POST['id_ciudad'] ?? 0;

if (empty($nombre_usuario) || $id_pais <= 0 || $id_ciudad <= 0) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
    exit;
}

// Verificar que la ciudad pertenezca al país (integridad referencial)
$check = mysqli_prepare($conexion, "SELECT id FROM ciudades WHERE id = ? AND id_pais = ?");
mysqli_stmt_bind_param($check, "ii", $id_ciudad, $id_pais);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);
if (mysqli_stmt_num_rows($check) == 0) {
    echo json_encode(['success' => false, 'message' => 'La ciudad no corresponde al país seleccionado']);
    exit;
}
mysqli_stmt_close($check);

// Insertar en registros_viajes
$query = "INSERT INTO registros_viajes (nombre_usuario, id_pais, id_ciudad, fecha_registro) VALUES (?, ?, ?, NOW())";
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "sii", $nombre_usuario, $id_pais, $id_ciudad);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Destino guardado exitosamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar: ' . mysqli_error($conexion)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>