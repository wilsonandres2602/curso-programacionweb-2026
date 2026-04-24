<?php
// get_registros.php – Devuelve los últimos 10 registros de viajes con nombres
header('Content-Type: application/json');
require_once 'db.php';

$sql = "
    SELECT
        rv.nombre_usuario,
        c.nombre  AS ciudad,
        p.nombre  AS pais
    FROM registros_viajes rv
    JOIN ciudades c ON c.id = rv.id_ciudad
    JOIN paises   p ON p.id = rv.id_pais
    ORDER BY rv.fecha_registro DESC
    LIMIT 10
";

$result   = $conn->query($sql);
$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}

echo json_encode($registros);
$conn->close();
