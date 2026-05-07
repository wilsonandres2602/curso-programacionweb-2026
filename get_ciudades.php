<?php
require_once "db.php";

if (!isset($_GET['id_pais']) || !is_numeric($_GET['id_pais'])) {
    echo "<option value=''>ID de país inválido</option>";
    exit;
}

$id_pais = intval($_GET['id_pais']);

$result = $conn->query("SELECT * FROM ciudades WHERE id_pais = $id_pais");

if (!$result) {
    echo "<option value=''>Error al cargar ciudades</option>";
    exit;
}

echo "<option value=''>Seleccione ciudad</option>";
while ($row = $result->fetch_assoc()) {
    echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
}
?>