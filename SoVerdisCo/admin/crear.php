<?php
require_once '../includes/db.php';
require_once 'includes/auth.php';
require_once 'layout.php';

$categorias = $conn->query("SELECT id, nombre FROM categorias ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo    = trim($_POST['titulo']);
    $slug      = trim($_POST['slug']) ?: generarSlug($titulo);
    $resumen   = trim($_POST['resumen']);
    $contenido = $_POST['contenido'];
    $categoria_id = (int)$_POST['categoria_id'];
    $estado    = $_POST['estado'] === 'publicado' ? 'publicado' : 'borrador';
    $imagen_url = trim($_POST['imagen_url']) ?: 'assets/img/default.jpg';

    if (!$titulo || !$resumen || !$contenido || !$categoria_id) {
        $error = 'Todos los campos obligatorios deben estar llenos.';
    } else {
        // Verificar slug único
        $check = $conn->prepare("SELECT id FROM articulos WHERE slug = ?");
        $check->bind_param('s', $slug);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $slug = $slug . '-' . rand(100, 999);
        }

        $stmt = $conn->prepare("INSERT INTO articulos (titulo, slug, resumen, contenido, imagen_url, categoria_id, autor_id, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $autor_id = $_SESSION['admin_id'];
        $stmt->bind_param('sssssiss', $titulo, $slug, $resumen, $contenido, $imagen_url, $categoria_id, $autor_id, $estado);
        if ($stmt->execute()) {
            header('Location: articulos.php?msg=saved');
            exit;
        } else {
            $error = 'Error al guardar: ' . $conn->error;
        }
    }
}

function generarSlug($texto) {
    $texto = strtolower(trim($texto));
    $texto = preg_replace('/[^a-z0-9\-]/', '-', $texto);
    $texto = preg_replace('/-+/', '-', $texto);
    return trim($texto, '-');
}

adminHeader('Crear artículo');
?>

<div class="card">
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="crear.php">
            <div class="form-group">
                <label class="form-label">Título *</label>
                <input type="text" name="titulo" class="form-control" required value="<?= htmlspecialchars($_POST['titulo'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Slug (URL amigable)</label>
                <input type="text" name="slug" class="form-control" placeholder="se-genera-automaticamente">
                <small>Déjalo vacío para autogenerarlo.</small>
            </div>
            <div class="form-group">
                <label class="form-label">Resumen corto *</label>
                <textarea name="resumen" class="form-control" rows="3" required><?= htmlspecialchars($_POST['resumen'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Contenido completo (HTML) *</label>
                <textarea name="contenido" class="form-control" rows="12" required><?= htmlspecialchars($_POST['contenido'] ?? '') ?></textarea>
                <small>Puedes usar etiquetas HTML: &lt;p&gt;, &lt;strong&gt;, &lt;ul&gt;, etc.</small>
            </div>
            <div class="form-group">
                <label class="form-label">URL de imagen destacada</label>
                <input type="text" name="imagen_url" class="form-control" placeholder="assets/img/noticias/ejemplo.jpg">
            </div>
            <div class="form-group">
                <label class="form-label">Categoría *</label>
                <select name="categoria_id" class="form-control" required>
                    <option value="">-- Seleccionar --</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($_POST['categoria_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-control">
                    <option value="borrador" <?= ($_POST['estado'] ?? '') === 'borrador' ? 'selected' : '' ?>>Borrador</option>
                    <option value="publicado" <?= ($_POST['estado'] ?? '') === 'publicado' ? 'selected' : '' ?>>Publicado</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-amber">Guardar artículo</button>
                <a href="articulos.php" class="btn btn-outline-green">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php adminFooter(); ?>