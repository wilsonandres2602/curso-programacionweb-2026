<?php
// ── Configuración de conexión ─────────────────────────────────────────────────
// Ajusta estos valores según tu entorno XAMPP local
define('DB_HOST', '127.0.0.1:3307');
define('DB_USER', 'root');       // usuario por defecto de XAMPP
define('DB_PASS', '');           // contraseña vacía por defecto en XAMPP
define('DB_NAME', 'mi_proyecto');

// ── Crear conexión ────────────────────────────────────────────────────────────
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$conn->set_charset('utf8mb4');

// ── Verificar conexión ────────────────────────────────────────────────────────
if ($conn->connect_error) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error de conexión: ' . $conn->connect_error]);
    exit;
}