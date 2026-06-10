<?php
require_once '../includes/db.php';
require_once 'includes/auth.php';

if ($_SESSION['admin_rol'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if ($id && $id != $_SESSION['admin_id']) {
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: usuarios.php?msg=eliminado');
} else {
    header('Location: usuarios.php?msg=error_self');
}
exit;