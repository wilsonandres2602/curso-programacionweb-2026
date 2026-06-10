<?php
require_once '../includes/db.php';
require_once 'includes/auth.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: articulos.php');
    exit;
}

$stmt = $conn->prepare("DELETE FROM articulos WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();

header('Location: articulos.php?msg=deleted');
exit;