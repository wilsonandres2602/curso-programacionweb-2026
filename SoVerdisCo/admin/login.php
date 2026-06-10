<?php
session_start();
require_once '../includes/db.php';

// Si ya está autenticado, redirigir al dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php'); exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$email || !$password) {
        $error = 'Por favor completa todos los campos.';
    } else {
        $stmt = $conn->prepare("SELECT id, nombre, password_hash, rol FROM usuarios WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && $password) {
            session_regenerate_id(true);
            $_SESSION['admin_id']   = $user['id'];
            $_SESSION['admin_name'] = $user['nombre'];
            $_SESSION['admin_rol']  = $user['rol'];
            header('Location: dashboard.php'); exit;
        } else {
            $error = 'Correo o contraseña incorrectos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Admin — SoVerdisCo</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="login-wrap">
    <div class="login-card">
        <div class="login-logo">
            <svg width="40" height="40" viewBox="0 0 28 28" fill="none" aria-hidden="true" style="margin:0 auto .5rem">
                <circle cx="14" cy="14" r="7" fill="#F5A623"/>
                <g stroke="#F5A623" stroke-width="1.8" stroke-linecap="round">
                    <line x1="14" y1="1" x2="14" y2="4"/><line x1="14" y1="24" x2="14" y2="27"/>
                    <line x1="1"  y1="14" x2="4"  y2="14"/><line x1="24" y1="14" x2="27" y2="14"/>
                    <line x1="4.93" y1="4.93" x2="7.05" y2="7.05"/><line x1="20.95" y1="20.95" x2="23.07" y2="23.07"/>
                    <line x1="23.07" y1="4.93" x2="20.95" y2="7.05"/><line x1="7.05" y1="20.95" x2="4.93" y2="23.07"/>
                </g>
            </svg>
            <div class="brand">SoVerdisCo</div>
            <div class="sub">Panel de Administración</div>
        </div>

        <?php if ($error): ?>
        <div class="alert alert-error" role="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php" data-validate novalidate>
            <div class="form-group">
                <label class="form-label" for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" class="form-control"
                    placeholder="admin@soverdisco.co" required autocomplete="email"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                <span class="form-error" role="alert"></span>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control"
                    placeholder="••••••••" required autocomplete="current-password">
                <span class="form-error" role="alert"></span>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                🔐 Ingresar al panel
            </button>
        </form>

        <p style="text-align:center;margin-top:1.5rem;font-size:.85rem;color:var(--text-muted)">
            <a href="../index.php" style="color:var(--green)">← Volver al sitio público</a>
        </p>

        <div style="margin-top:1.5rem;padding:1rem;background:var(--bg-light);border-radius:var(--radius-sm);font-size:.8rem;color:var(--text-muted)">
            <strong>Demo:</strong> admin@soverdisco.co / Admin2024$
        </div>
    </div>
</div>

<script src="../assets/js/main.js"></script>
</body>
</html>