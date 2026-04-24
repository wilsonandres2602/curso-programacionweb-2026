<?php
// login.php — Valida usuario y contraseña contra la BD
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    echo json_encode(['ok' => false, 'error' => 'Completa usuario y contraseña']);
    exit;
}

// Buscar usuario por username
$stmt = $conn->prepare(
    "SELECT id, nombre_completo, password_hash FROM usuarios WHERE username = ? LIMIT 1"
);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // No revelar si el usuario existe o no
    echo json_encode(['ok' => false, 'error' => 'Credenciales incorrectas']);
    $stmt->close();
    $conn->close();
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Verificar contraseña con bcrypt
if (password_verify($password, $user['password_hash'])) {
    echo json_encode([
        'ok'             => true,
        'nombre_completo'=> $user['nombre_completo'],
        'username'       => $username
    ]);
} else {
    echo json_encode(['ok' => false, 'error' => 'Credenciales incorrectas']);
}
