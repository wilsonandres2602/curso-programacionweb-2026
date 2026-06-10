<?php
require_once 'includes/db.php';
$pageTitle = 'Noticias & Blog';

$porPagina = 6;
$pagina    = max(1, (int)($_GET['p'] ?? 1));
$categoria = trim($_GET['cat'] ?? 'todos');
$busqueda  = trim($_GET['q']   ?? '');

$where = ["estado = 'publicado'"];
$params = []; $types = '';
if ($categoria !== 'todos') { $where[] = 'categoria_slug = ?'; $params[] = $categoria; $types .= 's'; }
if ($busqueda)              { $where[] = '(titulo LIKE ? OR resumen LIKE ? OR contenido LIKE ?)'; $like = "%$busqueda%"; $params = array_merge($params, [$like,$like,$like]); $types .= 'sss'; }

$sqlWhere = implode(' AND ', $where);

// Total para paginación
$stmtTotal = $conn->prepare("SELECT COUNT(*) FROM vista_articulos WHERE $sqlWhere");
if ($params) $stmtTotal->bind_param($types, ...$params);
$stmtTotal->execute();
$total = $stmtTotal->get_result()->fetch_row()[0];
$totalPags = max(1, ceil($total / $porPagina));
$pagina = min($pagina, $totalPags);
$offset = ($pagina - 1) * $porPagina;

// Artículos de esta página
$paramsPage = array_merge($params, [$porPagina, $offset]);
$typesPage  = $types . 'ii';
$stmtArt = $conn->prepare("SELECT * FROM vista_articulos WHERE $sqlWhere ORDER BY creado_en DESC LIMIT ? OFFSET ?");
$stmtArt->bind_param($typesPage, ...$paramsPage);
$stmtArt->execute();
$articulos = $stmtArt->get_result()->fetch_all(MYSQLI_ASSOC);

// Categorías para filtro
$categorias = $conn->query("SELECT c.*, COUNT(a.id) as total FROM categorias c LEFT JOIN articulos a ON a.categoria_id=c.id AND a.estado='publicado' GROUP BY c.id ORDER BY total DESC")->fetch_all(MYSQLI_ASSOC);
?>
<?php include 'includes/header.php'; ?>

<div class="page-hero">
    <div class="container">
        <nav class="breadcrumb"><a href="index.php">Inicio</a><span>/</span><span>Noticias</span></nav>
        <h1>Noticias & Blog Solar</h1>
        <p>Publicaciones de divulgación científica sobre energía solar fotovoltaica en Colombia.</p>
    </div>
</div>

<section class="section filter-section">
    <div class="container">

        <!-- Búsqueda -->
        <form method="GET" action="noticias.php" style="margin-bottom:1.5rem">
            <div class="search-box">
                <input type="text" id="search-input" name="q" class="search-input"
                    placeholder="Buscar artículos por título, tema o palabra clave..."
                    value="<?= htmlspecialchars($busqueda) ?>" autocomplete="off">
                <?php if ($categoria !== 'todos'): ?>
                <input type="hidden" name="cat" value="<?= htmlspecialchars($categoria) ?>">
                <?php endif; ?>
                <button type="submit" class="btn btn-primary">Buscar</button>
                <?php if ($busqueda || $categoria !== 'todos'): ?>
                <a href="noticias.php" class="btn btn-outline-green">Limpiar</a>
                <?php endif; ?>
            </div>
        </form>

        <!-- Filtro por categoría -->
        <div class="filter-bar" role="list" aria-label="Filtrar por categoría">
            <a href="noticias.php<?= $busqueda ? '?q='.urlencode($busqueda) : '' ?>"
               class="filter-pill <?= $categoria==='todos'?'active':'' ?>" role="listitem">
                Todos (<?= $total ?>)
            </a>
            <?php foreach ($categorias as $cat): ?>
            <a href="noticias.php?cat=<?= urlencode($cat['slug']) ?><?= $busqueda ? '&q='.urlencode($busqueda) : '' ?>"
               class="filter-pill <?= $categoria===$cat['slug']?'active':'' ?>"
               style="<?= $categoria===$cat['slug'] ? "background:{$cat['color']};border-color:{$cat['color']}" : '' ?>"
               role="listitem">
                <?= htmlspecialchars($cat['nombre']) ?> (<?= $cat['total'] ?>)
            </a>
            <?php endforeach; ?>
        </div>

        <!-- Grid de artículos -->
        <?php if (empty($articulos)): ?>
        <div style="text-align:center;padding:4rem 1rem;color:var(--text-muted)">
            <p style="font-size:2.5rem">📰</p>
            <h3>No se encontraron artículos</h3>
            <p>Prueba con otros términos de búsqueda o selecciona una categoría diferente.</p>
            <a href="noticias.php" class="btn btn-outline-green" style="margin-top:1rem">Ver todos los artículos</a>
        </div>
        <?php else: ?>
        <div class="article-grid" id="articles-grid">
            <?php foreach ($articulos as $art): ?>
            <article class="card article-card" data-category="<?= htmlspecialchars($art['categoria_slug']) ?>">
                <div class="card-img" style="background:var(--green-light);display:flex;align-items:center;justify-content:center;font-size:3.5rem;">☀️</div>
                <div class="card-body">
                    <div class="article-meta">
                        <span class="badge" style="background:<?= htmlspecialchars($art['categoria_color']) ?>22;color:<?= htmlspecialchars($art['categoria_color']) ?>">
                            <?= htmlspecialchars($art['categoria_nombre']) ?>
                        </span>
                        <span class="article-date"><?= date('d/m/Y', strtotime($art['creado_en'])) ?></span>
                    </div>
                    <h2 class="article-title">
                        <a href="articulo.php?slug=<?= htmlspecialchars($art['slug']) ?>">
                            <?= htmlspecialchars($art['titulo']) ?>
                        </a>
                    </h2>
                    <p class="article-excerpt"><?= htmlspecialchars(mb_substr($art['resumen'], 0, 140)) ?>…</p>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:auto">
                        <a href="articulo.php?slug=<?= htmlspecialchars($art['slug']) ?>" class="read-more">Leer artículo →</a>
                        <small style="color:var(--text-muted)">👁 <?= $art['vistas'] ?></small>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <!-- Paginación -->
        <?php if ($totalPags > 1): ?>
        <nav class="pagination" aria-label="Paginación de artículos">
            <?php if ($pagina > 1): ?>
            <a href="?p=<?= $pagina-1 ?>&cat=<?= urlencode($categoria) ?>&q=<?= urlencode($busqueda) ?>" class="page-btn" aria-label="Página anterior">‹</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPags; $i++): ?>
            <a href="?p=<?= $i ?>&cat=<?= urlencode($categoria) ?>&q=<?= urlencode($busqueda) ?>"
               class="page-btn <?= $i===$pagina?'active':'' ?>"
               aria-label="Página <?= $i ?>" <?= $i===$pagina?'aria-current="page"':'' ?>>
                <?= $i ?>
            </a>
            <?php endfor; ?>
            <?php if ($pagina < $totalPags): ?>
            <a href="?p=<?= $pagina+1 ?>&cat=<?= urlencode($categoria) ?>&q=<?= urlencode($busqueda) ?>" class="page-btn" aria-label="Página siguiente">›</a>
            <?php endif; ?>
        </nav>
        <?php endif; ?>
        <?php endif; ?>

    </div>
</section>

<?php include 'includes/footer.php'; ?>