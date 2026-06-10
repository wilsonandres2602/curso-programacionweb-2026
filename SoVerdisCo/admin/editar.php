<?php
require_once '../includes/db.php';
require_once 'layout.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: articulos.php'); exit; }

// Cargar artículo
$stmt = $conn->prepare("SELECT * FROM articulos WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$art = $stmt->get_result()->fetch_assoc();
if (!$art) { header('Location: articulos.php'); exit; }

// Categorías
$categorias = $conn->query("SELECT * FROM categorias ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);

$errores = [];
$exito   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo      = trim($_POST['titulo']      ?? '');
    $resumen     = trim($_POST['resumen']     ?? '');
    $contenido   = trim($_POST['contenido']   ?? '');
    $categoria_id= (int)($_POST['categoria_id'] ?? 0);
    $estado      = $_POST['estado'] === 'publicado' ? 'publicado' : 'borrador';
    $imagen_url  = trim($_POST['imagen_url']  ?? '') ?: $art['imagen_url'];

    // Validaciones
    if (!$titulo)       $errores['titulo']       = 'El título es obligatorio.';
    if (!$resumen)      $errores['resumen']       = 'El resumen es obligatorio.';
    if (!$contenido)    $errores['contenido']     = 'El contenido es obligatorio.';
    if (!$categoria_id) $errores['categoria_id']  = 'Selecciona una categoría.';

    if (empty($errores)) {
        // Regenerar slug solo si el título cambió
        $slug = $art['slug'];
        if ($titulo !== $art['titulo']) {
            $base = strtolower(preg_replace('/[^a-z0-9]+/i', '-', iconv('UTF-8','ASCII//TRANSLIT', $titulo)));
            $slug = trim($base, '-');
            // Verificar unicidad excluyendo el artículo actual
            $chk = $conn->prepare("SELECT id FROM articulos WHERE slug = ? AND id != ?");
            $chk->bind_param('si', $slug, $id);
            $chk->execute();
            if ($chk->get_result()->num_rows > 0) $slug .= '-' . $id;
        }

        $upd = $conn->prepare("UPDATE articulos SET titulo=?, slug=?, resumen=?, contenido=?, categoria_id=?, estado=?, imagen_url=? WHERE id=?");
        $upd->bind_param('ssssissi', $titulo, $slug, $resumen, $contenido, $categoria_id, $estado, $imagen_url, $id);

        if ($upd->execute()) {
            // Recargar datos actualizados
            $stmt->execute();
            $art = $stmt->get_result()->fetch_assoc();
            $exito = 'Artículo actualizado correctamente.';
        } else {
            $errores['db'] = 'Error al guardar: ' . $conn->error;
        }
    }
}

adminHeader('Editar artículo');
?>

<div style="margin-bottom:1.5rem">
    <a href="articulos.php" class="btn btn-outline-green btn-sm">← Volver a artículos</a>
</div>

<?php if ($exito): ?>
<div class="alert alert-success" role="alert">✅ <?= htmlspecialchars($exito) ?></div>
<?php endif; ?>
<?php if (!empty($errores['db'])): ?>
<div class="alert alert-error" role="alert">❌ <?= htmlspecialchars($errores['db']) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" action="editar.php?id=<?= $id ?>" data-validate novalidate>

            <div class="grid-2" style="gap:2rem">
                <!-- Columna izquierda -->
                <div>
                    <div class="form-group">
                        <label class="form-label" for="titulo">Título <span style="color:#dc3545">*</span></label>
                        <input type="text" id="titulo" name="titulo" class="form-control <?= isset($errores['titulo'])?'error':'' ?>"
                            value="<?= htmlspecialchars($_POST['titulo'] ?? $art['titulo']) ?>"
                            required maxlength="255" placeholder="Título del artículo">
                        <?php if (isset($errores['titulo'])): ?>
                        <span class="form-error visible"><?= htmlspecialchars($errores['titulo']) ?></span>
                        <?php else: ?>
                        <span class="form-error"></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="resumen">Resumen <span style="color:#dc3545">*</span></label>
                        <textarea id="resumen" name="resumen" class="form-control <?= isset($errores['resumen'])?'error':'' ?>"
                            rows="3" required placeholder="Breve descripción que aparece en el listado del blog"><?= htmlspecialchars($_POST['resumen'] ?? $art['resumen']) ?></textarea>
                        <?php if (isset($errores['resumen'])): ?>
                        <span class="form-error visible"><?= htmlspecialchars($errores['resumen']) ?></span>
                        <?php else: ?>
                        <span class="form-error"></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="contenido">Contenido completo <span style="color:#dc3545">*</span></label>
                        <textarea id="contenido" name="contenido" class="form-control <?= isset($errores['contenido'])?'error':'' ?>"
                            rows="12" required placeholder="Puedes usar etiquetas HTML: <p>, <strong>, <em>, <h3>, <ul>, <li>"><?= htmlspecialchars($_POST['contenido'] ?? $art['contenido']) ?></textarea>
                        <?php if (isset($errores['contenido'])): ?>
                        <span class="form-error visible"><?= htmlspecialchars($errores['contenido']) ?></span>
                        <?php else: ?>
                        <span class="form-error"></span>
                        <?php endif; ?>
                        <small style="color:var(--text-muted);font-size:.8rem">Soporta HTML básico: &lt;p&gt;, &lt;strong&gt;, &lt;em&gt;, &lt;h3&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;a&gt;</small>
                    </div>
                </div>

                <!-- Columna derecha -->
                <div>
                    <div class="form-group">
                        <label class="form-label" for="categoria_id">Categoría <span style="color:#dc3545">*</span></label>
                        <select id="categoria_id" name="categoria_id" class="form-control <?= isset($errores['categoria_id'])?'error':'' ?>" required>
                            <option value="">-- Selecciona --</option>
                            <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id'] ?>"
                                <?= ((int)($_POST['categoria_id'] ?? $art['categoria_id']) === (int)$cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nombre']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errores['categoria_id'])): ?>
                        <span class="form-error visible"><?= htmlspecialchars($errores['categoria_id']) ?></span>
                        <?php else: ?>
                        <span class="form-error"></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="estado">Estado de publicación</label>
                        <select id="estado" name="estado" class="form-control">
                            <option value="publicado" <?= ($_POST['estado'] ?? $art['estado']) === 'publicado' ? 'selected' : '' ?>>✅ Publicado</option>
                            <option value="borrador"  <?= ($_POST['estado'] ?? $art['estado']) === 'borrador'  ? 'selected' : '' ?>>📝 Borrador</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="imagen_url">URL de imagen de portada</label>
                        <input type="text" id="imagen_url" name="imagen_url" class="form-control"
                            value="<?= htmlspecialchars($_POST['imagen_url'] ?? $art['imagen_url']) ?>"
                            placeholder="assets/img/noticias/mi-imagen.jpg">
                        <small style="color:var(--text-muted);font-size:.8rem">Ruta relativa desde la raíz del proyecto</small>
                    </div>

                    <!-- Metadata (solo lectura) -->
                    <div style="background:var(--bg-light);border-radius:var(--radius-sm);padding:1.25rem;font-size:.85rem;margin-top:.5rem">
                        <h3 style="font-size:.85rem;color:var(--text-muted);margin-bottom:.75rem;text-transform:uppercase;letter-spacing:.06em">Metadata</h3>
                        <div style="display:flex;flex-direction:column;gap:.4rem;color:var(--text-muted)">
                            <div><strong>ID:</strong> #<?= $art['id'] ?></div>
                            <div><strong>Slug:</strong> <?= htmlspecialchars($art['slug']) ?></div>
                            <div><strong>Creado:</strong> <?= date('d/m/Y H:i', strtotime($art['creado_en'])) ?></div>
                            <div><strong>Actualizado:</strong> <?= date('d/m/Y H:i', strtotime($art['actualizado_en'])) ?></div>
                            <div><strong>Vistas:</strong> <?= $art['vistas'] ?></div>
                        </div>
                    </div>

                    <?php if ($art['estado'] === 'publicado'): ?>
                    <div style="margin-top:1rem">
                        <a href="../articulo.php?slug=<?= htmlspecialchars($art['slug']) ?>" target="_blank" rel="noopener"
                           class="btn btn-outline-green btn-sm" style="width:100%;justify-content:center">
                            🔗 Ver artículo publicado
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Botones de acción -->
            <hr style="margin:2rem 0;border-color:var(--border)">
            <div style="display:flex;gap:1rem;flex-wrap:wrap">
                <button type="submit" name="estado" value="publicado" class="btn btn-primary">
                    ✅ Guardar y publicar
                </button>
                <button type="submit" name="estado" value="borrador" class="btn btn-outline-green">
                    📝 Guardar como borrador
                </button>
                <a href="eliminar.php?id=<?= $id ?>" class="btn btn-danger confirm-delete" style="margin-left:auto">
                    🗑 Eliminar artículo
                </a>
            </div>

        </form>
    </div>
</div>

<?php adminFooter(); ?>