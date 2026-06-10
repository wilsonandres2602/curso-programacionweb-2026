<?php
require_once '../includes/db.php';
require_once 'includes/auth.php';
require_once 'layout.php';

$mensaje = $_GET['msg'] ?? '';
$proyectos = $conn->query("SELECT * FROM proyectos ORDER BY capacidad_mw DESC")->fetch_all(MYSQLI_ASSOC);

adminHeader('Proyectos solares');
?>

<?php if ($mensaje === 'creado'): ?>
<div class="alert alert-success">✅ Proyecto creado correctamente.</div>
<?php elseif ($mensaje === 'actualizado'): ?>
<div class="alert alert-success">✅ Proyecto actualizado correctamente.</div>
<?php elseif ($mensaje === 'eliminado'): ?>
<div class="alert alert-success">✅ Proyecto eliminado.</div>
<?php endif; ?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <h2 style="font-size:1.25rem">Listado de proyectos fotovoltaicos</h2>
    <a href="proyecto_nuevo.php" class="btn btn-amber">➕ Nuevo proyecto</a>
</div>

<div class="card">
    <div class="card-body" style="padding:0">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th><th>Capacidad (MW)</th><th>Región</th><th>Dep./Munic.</th>
                        <th>Empresa</th><th>Estado</th><th>Año</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($proyectos)): ?>
                        <tr><td colspan="8" style="text-align:center">No hay proyectos registrados.</td></tr>
                    <?php else: ?>
                        <?php foreach ($proyectos as $p): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
                            <td><?= number_format($p['capacidad_mw'], 0, ',', '.') ?> MW</td>
                            <td><?= htmlspecialchars($p['region']) ?></td>
                            <td><?= htmlspecialchars($p['departamento']) ?> / <?= htmlspecialchars($p['municipio']) ?></td>
                            <td><?= htmlspecialchars($p['empresa']) ?></td>
                            <td>
                                <span class="badge estado-<?= $p['estado'] ?>">
                                    <?= ucfirst(str_replace(['operacion','pruebas','construccion','aprobado'],['En operación','En pruebas','Construcción','Aprobado'], $p['estado'])) ?>
                                </span>
                            </td>
                            <td><?= $p['anio_inicio'] ?? '—' ?></td>
                            <td class="td-actions">
                                <a href="proyecto_editar.php?id=<?= $p['id'] ?>" class="btn btn-amber btn-sm">✏️</a>
                                <a href="proyecto_eliminar.php?id=<?= $p['id'] ?>" class="btn btn-danger btn-sm confirm-delete">🗑</a>
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