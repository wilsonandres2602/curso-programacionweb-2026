<?php
require_once '../includes/db.php';
require_once 'includes/auth.php';
require_once 'layout.php';

if ($_SESSION['admin_rol'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$mensaje = $_GET['msg'] ?? '';
$usuarios = $conn->query("SELECT id, nombre, email, rol, creado_en FROM usuarios ORDER BY id")->fetch_all(MYSQLI_ASSOC);

adminHeader('Usuarios');
?>

<?php if ($mensaje === 'creado'): ?>
<div class="alert alert-success">✅ Usuario creado correctamente.</div>
<?php elseif ($mensaje === 'actualizado'): ?>
<div class="alert alert-success">✅ Usuario actualizado correctamente.</div>
<?php elseif ($mensaje === 'eliminado'): ?>
<div class="alert alert-success">✅ Usuario eliminado.</div>
<?php elseif ($mensaje === 'error_self'): ?>
<div class="alert alert-error">❌ No puedes eliminar tu propio usuario.</div>
<?php endif; ?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <h2 style="font-size:1.25rem">Usuarios del sistema</h2>
    <a href="usuario_nuevo.php" class="btn btn-amber">➕ Nuevo usuario</a>
</div>

<div class="card">
    <div class="card-body" style="padding:0">
        <div class="table-wrap">
            <table>
                <thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Creado</th><th>Acciones</th></thead>
                <tbody>
                <?php if (empty($usuarios)): ?>
                    <tr><td colspan="6" style="text-align:center">No hay usuarios registrados.</td></tr>
                <?php else: ?>
                    <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td>#<?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['nombre']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><span class="badge <?= $u['rol']==='admin' ? 'badge-amber' : 'badge-gray' ?>"><?= ucfirst($u['rol']) ?></span></td>
                        <td><?= date('d/m/Y', strtotime($u['creado_en'])) ?></td>
                        <td class="td-actions">
                            <a href="usuario_editar.php?id=<?= $u['id'] ?>" class="btn btn-amber btn-sm">✏️</a>
                            <?php if ($u['id'] != $_SESSION['admin_id']): ?>
                            <a href="usuario_eliminar.php?id=<?= $u['id'] ?>" class="btn btn-danger btn-sm confirm-delete">🗑</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php adminFooter(); ?>