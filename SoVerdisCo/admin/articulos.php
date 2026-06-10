<?php
// Activar reporte de errores para depuración (eliminar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../includes/db.php';
require_once 'includes/auth.php';
require_once 'layout.php';

// Verificar conexión a BD
if (!$conn || $conn->connect_error) {
    die('Error de conexión a la base de datos: ' . ($conn->connect_error ?? 'desconocido'));
}

$busqueda = trim($_GET['q'] ?? '');
$filtroEstado = $_GET['estado'] ?? 'todos';

$where = ['1=1'];
$params = []; 
$types = '';

if ($busqueda !== '') { 
    $where[] = '(a.titulo LIKE ? OR c.nombre LIKE ?)'; 
    $like = "%$busqueda%"; 
    $params[] = $like; 
    $params[] = $like; 
    $types .= 'ss'; 
}
if ($filtroEstado !== 'todos') { 
    $where[] = 'a.estado = ?'; 
    $params[] = $filtroEstado; 
    $types .= 's'; 
}

$sql = "SELECT a.id, a.titulo, a.estado, a.vistas, a.creado_en, c.nombre AS cat, u.nombre AS autor
        FROM articulos a
        JOIN categorias c ON a.categoria_id = c.id
        JOIN usuarios u ON a.autor_id = u.id
        WHERE " . implode(' AND ', $where) . "
        ORDER BY a.creado_en DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die('Error en prepare: ' . $conn->error);
}
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$articulos = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$msg = $_GET['msg'] ?? '';
adminHeader('Artículos');
?>

<?php if ($msg === 'deleted'): ?>
<div class="alert alert-success">✅ Artículo eliminado correctamente.</div>
<?php elseif ($msg === 'saved'): ?>
<div class="alert alert-success">✅ Artículo guardado correctamente.</div>
<?php endif; ?>

<div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem">
    <form method="GET" action="articulos.php" style="display:flex;gap:.75rem;flex-wrap:wrap">
        <input type="text" name="q" class="form-control" style="width:260px"
            placeholder="Buscar por título o categoría..." value="<?= htmlspecialchars($busqueda) ?>">
        <select name="estado" class="form-control" style="width:160px">
            <option value="todos" <?= $filtroEstado==='todos'?'selected':'' ?>>Todos</option>
            <option value="publicado" <?= $filtroEstado==='publicado'?'selected':'' ?>>Publicados</option>
            <option value="borrador"  <?= $filtroEstado==='borrador'?'selected':'' ?>>Borradores</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
        <?php if ($busqueda || $filtroEstado !== 'todos'): ?>
        <a href="articulos.php" class="btn btn-outline-green btn-sm">Limpiar</a>
        <?php endif; ?>
    </form>
    <a href="crear.php" class="btn btn-amber">➕ Nuevo artículo</a>
</div>

<div class="card">
    <div class="card-body" style="padding:0">
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr><th>ID</th><th>Título</th><th>Categoría</th><th>Autor</th><th>Fecha</th><th>Vistas</th><th>Estado</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                <?php if (empty($articulos)): ?>
                    <tr><td colspan="8" style="text-align:center;padding:2rem">No se encontraron artículos.</td></tr>
                <?php else: ?>
                    <?php foreach ($articulos as $a): ?>
                    <tr>
                        <td>#<?= $a['id'] ?></td>
                        <td style="max-width:300px;font-weight:600"><?= htmlspecialchars(mb_substr($a['titulo'], 0, 65)) ?>…</td>
                        <td><?= htmlspecialchars($a['cat']) ?></td>
                        <td><?= htmlspecialchars($a['autor']) ?></td>
                        <td><?= date('d/m/Y', strtotime($a['creado_en'])) ?></td>
                        <td>👁 <?= $a['vistas'] ?></td>
                        <td><span class="badge <?= $a['estado']==='publicado' ? 'badge-green' : 'badge-gray' ?>"><?= ucfirst($a['estado']) ?></span></td>
                        <td class="td-actions">
                            <a href="editar.php?id=<?= $a['id'] ?>" class="btn btn-amber btn-sm">✏️</a>
                            <a href="eliminar.php?id=<?= $a['id'] ?>" class="btn btn-danger btn-sm confirm-delete">🗑</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<p style="font-size:.82rem;margin-top:.75rem">Mostrando <?= count($articulos) ?> artículo(s)</p>

<?php adminFooter(); ?>