<?php
require_once '../includes/db.php';
require_once 'includes/auth.php';
require_once 'layout.php';

$mensaje = '';
$paginas_disponibles = ['inicio' => 'Página de Inicio', 'energia-solar' => 'Energía Solar'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pagina = $_POST['pagina'];
    $titulo = trim($_POST['titulo']);
    $contenido = $_POST['contenido'];

    if (isset($paginas_disponibles[$pagina])) {
        $stmt = $conn->prepare("INSERT INTO contenido_paginas (pagina, titulo, contenido) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE titulo = VALUES(titulo), contenido = VALUES(contenido)");
        $stmt->bind_param('sss', $pagina, $titulo, $contenido);
        if ($stmt->execute()) {
            $mensaje = '✅ Contenido actualizado correctamente.';
        } else {
            $mensaje = '❌ Error al guardar: ' . $conn->error;
        }
    }
}

// Obtener contenidos actuales
$contenidos = [];
$res = $conn->query("SELECT pagina, titulo, contenido FROM contenido_paginas");
while ($row = $res->fetch_assoc()) {
    $contenidos[$row['pagina']] = $row;
}

adminHeader('Editar páginas estáticas');
?>

<?php if ($mensaje): ?>
<div class="alert alert-success"><?= $mensaje ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <p>Edita el contenido principal de las páginas <strong>Inicio</strong> y <strong>Energía Solar</strong>. Los cambios se reflejarán automáticamente en el sitio público.</p>
    </div>
</div>

<?php foreach ($paginas_disponibles as $clave => $nombre): 
    $titulo_actual = $contenidos[$clave]['titulo'] ?? ($clave === 'inicio' ? 'Colombia avanza hacia la energía del futuro' : 'Energía Solar en Colombia');
    $contenido_actual = $contenidos[$clave]['contenido'] ?? ($clave === 'inicio' ? '<p>Explora el potencial fotovoltaico de nuestro país...</p>' : '<p>Comprende cómo funciona la energía fotovoltaica...</p>');
?>
<div class="card" style="margin-top:2rem">
    <div class="card-body">
        <h2 style="font-size:1.2rem; margin-bottom:1rem"><?= $nombre ?></h2>
        <form method="POST">
            <input type="hidden" name="pagina" value="<?= $clave ?>">
            <div class="form-group">
                <label class="form-label">Título principal</label>
                <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($titulo_actual) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Contenido (HTML permitido)</label>
                <textarea name="contenido" class="form-control" rows="8" required><?= htmlspecialchars($contenido_actual) ?></textarea>
                <small>Puedes usar etiquetas como &lt;p&gt;, &lt;strong&gt;, &lt;ul&gt;, etc.</small>
            </div>
            <button type="submit" class="btn btn-amber">Guardar cambios</button>
        </form>
    </div>
</div>
<?php endforeach; ?>

<?php adminFooter(); ?>