<?php
require_once '../includes/db.php';
require_once 'includes/auth.php';
require_once 'layout.php';

if ($_SESSION['admin_rol'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: usuarios.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
if (!$usuario) {
    header('Location: usuarios.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email  = trim($_POST['email']);
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    if (!$nombre || !$email) {
        $error = 'Nombre y correo son obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Correo electrónico no válido.';
    } else {
        $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $check->bind_param('si', $email, $id);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = 'Ya existe otro usuario con ese correo.';
        } else {
            if (!empty($password)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE usuarios SET nombre=?, email=?, password_hash=?, rol=? WHERE id=?");
                $stmt->bind_param('ssssi', $nombre, $email, $hash, $rol, $id);
            } else {
                $stmt = $conn->prepare("UPDATE usuarios SET nombre=?, email=?, rol=? WHERE id=?");
                $stmt->bind_param('sssi', $nombre, $email, $rol, $id);
            }
            if ($stmt->execute()) {
                header('Location: usuarios.php?msg=actualizado');
                exit;
            } else {
                $error = 'Error al actualizar: ' . $conn->error;
            }
        }
    }
}

adminHeader('Editar usuario');
?>

<div class="card">
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label class="form-label">Nombre completo *</label>
                <input type="text" name="nombre" class="form-control" required value="<?= htmlspecialchars($usuario['nombre']) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Correo electrónico *</label>
                <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($usuario['email']) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Nueva contraseña (dejar en blanco para no cambiar)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Rol</label>
                <select name="rol" class="form-control">
                    <option value="editor" <?= $usuario['rol']=='editor'?'selected':'' ?>>Editor</option>
                    <option value="admin" <?= $usuario['rol']=='admin'?'selected':'' ?>>Administrador</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-amber">Actualizar usuario</button>
                <a href="usuarios.php" class="btn btn-outline-green">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php adminFooter(); ?>