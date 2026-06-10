<?php
require_once '../includes/db.php';
require_once 'layout.php';

// Stats
$totalArt    = $conn->query("SELECT COUNT(*) FROM articulos")->fetch_row()[0];
$publicados  = $conn->query("SELECT COUNT(*) FROM articulos WHERE estado='publicado'")->fetch_row()[0];
$borradores  = $conn->query("SELECT COUNT(*) FROM articulos WHERE estado='borrador'")->fetch_row()[0];
$totalUsers  = $conn->query("SELECT COUNT(*) FROM usuarios")->fetch_row()[0];

// Últimos 5 artículos
$recientes = $conn->query("SELECT a.id, a.titulo, a.estado, a.creado_en, c.nombre AS cat FROM articulos a JOIN categorias c ON a.categoria_id=c.id ORDER BY a.creado_en DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

adminHeader('Dashboard');
?>

<!-- Stat cards -->
<div class="admin-stats">
    <div class="admin-stat">
        <div class="num"><?= $totalArt ?></div>
        <div class="label">Total artículos</div>
    </div>
    <div class="admin-stat" style="border-color:var(--green)">
        <div class="num" style="color:var(--green)"><?= $publicados ?></div>
        <div class="label">Publicados</div>
    </div>
    <div class="admin-stat" style="border-color:var(--text-muted)">
        <div class="num" style="color:var(--text-muted)"><?= $borradores ?></div>
        <div class="label">Borradores</div>
    </div>
    <div class="admin-stat" style="border-color:var(--blue)">
        <div class="num" style="color:var(--blue)"><?= $totalUsers ?></div>
        <div class="label">Usuarios admin</div>
    </div>
</div>

<!-- Quick actions -->
<div style="display:flex;gap:1rem;margin-bottom:2rem;flex-wrap:wrap">
    <a href="crear.php" class="btn btn-amber">➕ Nuevo artículo</a>
    <a href="articulos.php" class="btn btn-outline-green">📝 Gestionar artículos</a>
    <a href="usuarios.php" class="btn btn-outline-green">👤 Gestionar usuarios</a>
</div>

<!-- Recent articles -->
<div class="card">
    <div class="card-body">
        <h2 style="font-size:1.05rem;margin-bottom:1.25rem">Últimas publicaciones</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Título</th><th>Categoría</th><th>Fecha</th><th>Estado</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recientes as $a): ?>
                    <tr>
                        <td>#<?= $a['id'] ?></td>
                        <td style="max-width:280px"><?= htmlspecialchars(mb_substr($a['titulo'], 0, 60)) ?>…</td>
                        <td><?= htmlspecialchars($a['cat']) ?></td>
                        <td><?= date('d/m/Y', strtotime($a['creado_en'])) ?></td>
                        <td>
                            <span class="badge <?= $a['estado']==='publicado' ? 'badge-green' : 'badge-gray' ?>">
                                <?= ucfirst($a['estado']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="td-actions">
                                <a href="editar.php?id=<?= $a['id'] ?>" class="btn btn-amber btn-sm">✏️</a>
                                <a href="eliminar.php?id=<?= $a['id'] ?>" class="btn btn-danger btn-sm confirm-delete">🗑</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem">
            <a href="articulos.php" class="read-more">Ver todos los artículos →</a>
        </div>
    </div>
</div>

<?php adminFooter(); ?>