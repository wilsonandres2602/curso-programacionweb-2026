<?php
require_once 'includes/db.php';
$pageTitle = 'Artículo';

$slug = trim($_GET['slug'] ?? '');
if (!$slug) { header('Location: noticias.php'); exit; }

$stmt = $conn->prepare("SELECT * FROM vista_articulos WHERE slug = ? AND estado = 'publicado'");
$stmt->bind_param('s', $slug);
$stmt->execute();
$art = $stmt->get_result()->fetch_assoc();

if (!$art) { header('Location: noticias.php'); exit; }

// Incrementar vistas
$conn->query("UPDATE articulos SET vistas = vistas + 1 WHERE slug = '" . $conn->real_escape_string($slug) . "'");

$pageTitle = $art['titulo'];

// Artículos relacionados (misma categoría)
$stmtRel = $conn->prepare("SELECT id, titulo, slug, resumen, creado_en, categoria_color, categoria_nombre FROM vista_articulos WHERE categoria_slug = ? AND slug != ? AND estado = 'publicado' ORDER BY creado_en DESC LIMIT 3");
$stmtRel->bind_param('ss', $art['categoria_slug'], $slug);
$stmtRel->execute();
$relacionados = $stmtRel->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<?php include 'includes/header.php'; ?>

<div class="page-hero" style="padding:2rem 1.5rem">
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Inicio</a><span>/</span>
            <a href="noticias.php">Noticias</a><span>/</span>
            <span><?= htmlspecialchars(mb_substr($art['titulo'], 0, 50)) ?>…</span>
        </nav>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="grid-2" style="grid-template-columns:2fr 1fr;gap:3rem;align-items:start">

            <!-- ── Article body ──────────────────────────── -->
            <article>
                <div style="margin-bottom:1.25rem;display:flex;gap:.75rem;align-items:center;flex-wrap:wrap">
                    <span class="badge" style="background:<?= htmlspecialchars($art['categoria_color']) ?>22;color:<?= htmlspecialchars($art['categoria_color']) ?>">
                        <?= htmlspecialchars($art['categoria_nombre']) ?>
                    </span>
                    <span style="font-size:.85rem;color:var(--text-muted)">
                        📅 <?= date('d \d\e F \d\e Y', strtotime($art['creado_en'])) ?>
                    </span>
                    <span style="font-size:.85rem;color:var(--text-muted)">
                        ✍️ <?= htmlspecialchars($art['autor_nombre']) ?>
                    </span>
                    <span style="font-size:.85rem;color:var(--text-muted)">👁 <?= $art['vistas'] ?> lecturas</span>
                </div>

                <h1 style="font-size:clamp(1.6rem,3.5vw,2.4rem);margin-bottom:1.25rem">
                    <?= htmlspecialchars($art['titulo']) ?>
                </h1>

                <p style="font-size:1.1rem;color:var(--text-muted);border-left:4px solid var(--amber);padding-left:1rem;margin-bottom:2rem;font-style:italic">
                    <?= htmlspecialchars($art['resumen']) ?>
                </p>

                <!-- Cover image placeholder -->
                <div style="background:var(--green-light);border-radius:var(--radius);height:280px;display:flex;align-items:center;justify-content:center;font-size:5rem;margin-bottom:2rem;">
                    ☀️
                </div>

                <div class="article-content" style="font-size:1.02rem;line-height:1.85;color:var(--text)">
                    <?= $art['contenido'] /* contenido ya es HTML sanitizado desde admin */ ?>
                </div>

                <hr style="margin:2.5rem 0;border-color:var(--border)">

                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem">
                    <a href="noticias.php" class="btn btn-outline-green">← Volver al blog</a>
                    <div style="display:flex;gap:.75rem;font-size:.88rem;color:var(--text-muted)">
                        <span>Compartir:</span>
                        <a href="https://twitter.com/intent/tweet?text=<?= urlencode($art['titulo']) ?>&url=<?= urlencode('http://localhost/soVerdisco/articulo.php?slug='.$art['slug']) ?>" target="_blank" rel="noopener" style="color:var(--blue)">Twitter/X</a>
                        <a href="https://wa.me/?text=<?= urlencode($art['titulo'].' — http://localhost/soVerdisco/articulo.php?slug='.$art['slug']) ?>" target="_blank" rel="noopener" style="color:var(--green)">WhatsApp</a>
                    </div>
                </div>
            </article>

            <!-- ── Sidebar ───────────────────────────────── -->
            <aside>
                <?php if (!empty($relacionados)): ?>
                <div class="card" style="margin-bottom:1.5rem">
                    <div class="card-body">
                        <h3 style="font-size:1rem;margin-bottom:1rem;color:var(--text-muted);text-transform:uppercase;font-size:.8rem;letter-spacing:.08em">Artículos relacionados</h3>
                        <div style="display:flex;flex-direction:column;gap:1.25rem">
                            <?php foreach ($relacionados as $r): ?>
                            <div style="border-bottom:1px solid var(--border);padding-bottom:1rem">
                                <span class="badge" style="background:<?= htmlspecialchars($r['categoria_color']) ?>22;color:<?= htmlspecialchars($r['categoria_color']) ?>;margin-bottom:.4rem">
                                    <?= htmlspecialchars($r['categoria_nombre']) ?>
                                </span>
                                <a href="articulo.php?slug=<?= htmlspecialchars($r['slug']) ?>" style="font-weight:600;color:var(--text);font-size:.92rem;display:block;margin:.25rem 0">
                                    <?= htmlspecialchars($r['titulo']) ?>
                                </a>
                                <small style="color:var(--text-muted)"><?= date('d/m/Y', strtotime($r['creado_en'])) ?></small>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="card" style="background:var(--bg-dark);color:var(--white)">
                    <div class="card-body">
                        <h3 style="color:var(--amber);font-size:1rem;margin-bottom:.75rem">¿Cuánto ahorrarías con solar?</h3>
                        <p style="color:rgba(255,255,255,.7);font-size:.88rem;margin-bottom:1rem">Usa nuestra calculadora fotovoltaica y obtén un informe técnico personalizado en segundos.</p>
                        <a href="calculadora.php" class="btn btn-amber btn-sm" style="width:100%;justify-content:center">Calcular ahorro →</a>
                    </div>
                </div>
            </aside>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>