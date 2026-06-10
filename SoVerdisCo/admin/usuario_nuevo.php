<?php
require_once '../includes/db.php';
require_once 'includes/auth.php';
require_once 'layout.php';

if ($_SESSION['admin_rol'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email  = trim($_POST['email']);
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    if (!$nombre || !$email || !$password) {
        $error = 'Todos los campos son obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Correo electrónico no válido.';
    } else {
        $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $check->bind_param('s', $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = 'Ya existe un usuario con ese correo.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password_hash, rol) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssss', $nombre, $email, $hash, $rol);
            if ($stmt->execute()) {
                header('Location: usuarios.php?msg=creado');
                exit;
            } else {
                $error = 'Error al guardar: ' . $conn->error;
            }
        }
    }
}

adminHeader('Nuevo usuario');
?>

<div class="card">
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label class="form-label">Nombre completo *</label>
                <input type="text" name="nombre" class="form-control" required value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Correo electrónico *</label>
                <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Contraseña *</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Rol</label>
                <select name="rol" class="form-control">
                    <option value="editor" <?= ($_POST['rol']??'')=='editor'?'selected':'' ?>>Editor</option>
                    <option value="admin" <?= ($_POST['rol']??'')=='admin'?'selected':'' ?>>Administrador</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-amber">Crear usuario</button>
                <a href="usuarios.php" class="btn btn-outline-green">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php adminFooter(); ?>