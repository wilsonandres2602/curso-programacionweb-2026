<?php
require_once '../includes/db.php';
require_once 'includes/auth.php';

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt = $conn->prepare("DELETE FROM proyectos WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
}
header('Location: proyectos.php?msg=eliminado');
exit;