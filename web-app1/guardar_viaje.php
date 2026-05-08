<?php
// ============================================================
//  GUARDAR_VIAJE.PHP
//  RÚBRICA #3 - Inserta el registro del formulario en la BD
// ============================================================
include('db.php');

$nombre    = isset($_POST['nombre'])    ? htmlspecialchars(trim($_POST['nombre'])) : '';
$id_pais   = isset($_POST['id_pais'])   ? intval($_POST['id_pais'])                : 0;
$id_ciudad = isset($_POST['id_ciudad']) ? intval($_POST['id_ciudad'])              : 0;

if ($nombre === '' || $id_pais <= 0 || $id_ciudad <= 0) {
    echo json_encode(['ok' => false, 'error' => 'Datos incompletos.']);
    exit;
}

$query     = "INSERT INTO registros_viajes (nombre_usuario, id_pais, id_ciudad)
              VALUES ('$nombre', $id_pais, $id_ciudad)";
$resultado = mysqli_query($conexion, $query);

if ($resultado) {
    $newId = mysqli_insert_id($conexion);
    $q2    = "SELECT p.nombre AS pais, c.nombre AS ciudad
              FROM registros_viajes rv
              JOIN paises   p ON p.id = rv.id_pais
              JOIN ciudades c ON c.id = rv.id_ciudad
              WHERE rv.id = $newId";
    $res2  = mysqli_query($conexion, $q2);
    $row   = mysqli_fetch_assoc($res2);
    echo json_encode([
        'ok'      => true,
        'usuario' => $nombre,
        'pais'    => $row['pais'],
        'ciudad'  => $row['ciudad']
    ]);
} else {
    echo json_encode(['ok' => false, 'error' => mysqli_error($conexion)]);
}

mysqli_close($conexion);
?>
