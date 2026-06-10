<?php
require_once 'includes/db.php';
$pageTitle = 'Inicio';

// Obtener contenido dinámico de la página de inicio
$stmt = $conn->prepare("SELECT titulo, contenido FROM contenido_paginas WHERE pagina = 'inicio'");
$stmt->execute();
$inicio = $stmt->get_result()->fetch_assoc();
if (!$inicio) {
    // Valores por defecto si no existen en la BD
    $inicio = [
        'titulo'    => 'Colombia avanza hacia la <span>energía del futuro</span>',
        'contenido' => '<p>Explora el potencial fotovoltaico de nuestro país: datos reales, proyectos activos, calculadora de ahorro y el marco normativo que impulsa la transición energética.</p>'
    ];
}

// Estadísticas dinámicas
$totalArticulos = $conn->query("SELECT COUNT(*) FROM articulos WHERE estado='publicado'")->fetch_row()[0];

// Últimas 3 noticias publicadas
$stmtNews = $conn->query("SELECT id, titulo, slug, resumen, imagen_url, creado_en, categoria_nombre, categoria_color FROM vista_articulos WHERE estado='publicado' ORDER BY creado_en DESC LIMIT 3");
$noticias = $stmtNews->fetch_all(MYSQLI_ASSOC);
?>
<?php include 'includes/header.php'; ?>

<!-- ── Hero (contenido dinámico) ───────────────────────────────── -->
<section class="hero">
    <div class="hero-inner">
        <div class="hero-text">
            <span class="hero-eyebrow">Energía solar en Colombia</span>
            <h1><?= $inicio['titulo'] ?> <!– el <span> ya viene incluido si se guardó así –></h1>
            <?= $inicio['contenido'] ?>
            <div class="hero-actions">
                <a href="panorama.php" class="btn btn-amber">Ver panorama ☀</a>
                <a href="calculadora.php" class="btn btn-outline">Calculadora solar →</a>
            </div>
        </div>
        <div class="hero-visual" aria-hidden="true">
            <svg class="hero-svg" viewBox="0 0 400 320" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Solar panel array illustration -->
                <rect x="20" y="160" width="360" height="120" rx="8" fill="#0F2D1A" opacity=".6"/>
                <?php
                $colors = ['#1B8A5A','#156b47','#0F5C3C'];
                for ($row = 0; $row < 3; $row++) {
                    for ($col = 0; $col < 6; $col++) {
                        $x = 30 + $col * 58;
                        $y = 170 + $row * 36;
                        $c = $colors[$row % 3];
                        echo "<rect x='$x' y='$y' width='50' height='28' rx='3' fill='$c' opacity='.85'/>";
                        echo "<line x1='".($x+17)."' y1='$y' x2='".($x+17)."' y2='".($y+28)."' stroke='#0a1f10' stroke-width='.8'/>";
                        echo "<line x1='".($x+34)."' y1='$y' x2='".($x+34)."' y2='".($y+28)."' stroke='#0a1f10' stroke-width='.8'/>";
                        echo "<line x1='$x' y1='".($y+9)."' x2='".($x+50)."' y2='".($y+9)."' stroke='#0a1f10' stroke-width='.8'/>";
                        echo "<line x1='$x' y1='".($y+19)."' x2='".($x+50)."' y2='".($y+19)."' stroke='#0a1f10' stroke-width='.8'/>";
                    }
                }
                ?>
                <circle cx="340" cy="70" r="40" fill="#F5A623" opacity=".15"/>
                <circle cx="340" cy="70" r="24" fill="#F5A623" opacity=".9"/>
                <?php
                for ($i = 0; $i < 8; $i++) {
                    $angle = $i * 45;
                    $rad = deg2rad($angle);
                    $x1 = 340 + cos($rad) * 30; $y1 = 70 + sin($rad) * 30;
                    $x2 = 340 + cos($rad) * 46; $y2 = 70 + sin($rad) * 46;
                    echo "<line x1='$x1' y1='$y1' x2='$x2' y2='$y2' stroke='#F5A623' stroke-width='2.5' stroke-linecap='round' opacity='.7'/>";
                }
                ?>
                <path d="M200 150 L200 162" stroke="#F5A623" stroke-width="2" marker-end="url(#arr)" opacity=".8"/>
                <defs><marker id="arr" viewBox="0 0 8 8" refX="4" refY="4" markerWidth="5" markerHeight="5" orient="auto"><path d="M1 1l6 3-6 3z" fill="#F5A623"/></marker></defs>
                <text x="30" y="305" font-size="12" fill="rgba(255,255,255,.5)" font-family="system-ui">🌿 CO₂ evitado   ⚡ 1.594 MW instalados</text>
            </svg>
        </div>
    </div>
</section>

<!-- ── Stats Bar (estático) ───────────────────────────────────── -->
<div class="stats-bar" role="region" aria-label="Estadísticas nacionales">
    <div class="container">
        <div class="stat-item">
            <div class="stat-icon" aria-hidden="true">⚡</div>
            <div>
                <div class="stat-num" id="stat-mw">1.594 MW</div>
                <div class="stat-label">Capacidad fotovoltaica instalada</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon" aria-hidden="true">🏭</div>
            <div>
                <div class="stat-num">98</div>
                <div class="stat-label">Proyectos en operación / pruebas</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon" aria-hidden="true">🌿</div>
            <div>
                <div class="stat-num">7,6%</div>
                <div class="stat-label">De la matriz eléctrica nacional</div>
            </div>
        </div>
    </div>
</div>

<!-- ── Why Solar (estático) ───────────────────────────────────── -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">¿Por qué energía solar?</span>
            <h2>Colombia: condiciones únicas para la fotovoltaica</h2>
            <p>Posición ecuatorial, alta irradiación y un marco normativo sólido hacen del país uno de los mercados más atractivos de América Latina.</p>
        </div>
        <div class="grid-3">
            <div class="card">
                <div class="card-body">
                    <div style="font-size:2.5rem;margin-bottom:.75rem">☀️</div>
                    <h3>4,5 kWh/m²/día</h3>
                    <p style="color:var(--text-muted)">Radiación horizontal global promedio nacional, superior a la media europea de 3,1 kWh/m²/día.</p>
                    <a href="energia-solar.php" class="read-more">Conocer más →</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div style="font-size:2.5rem;margin-bottom:.75rem">💰</div>
                    <h3>Ahorro hasta 60%</h3>
                    <p style="color:var(--text-muted)">En factura de energía para instalaciones residenciales y comerciales con sistemas bien dimensionados.</p>
                    <a href="calculadora.php" class="read-more">Calcular ahorro →</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div style="font-size:2.5rem;margin-bottom:.75rem">📋</div>
                    <h3>Ley 1715 vigente</h3>
                    <p style="color:var(--text-muted)">Incentivos tributarios: 50% deducción en renta, exención de IVA y arancel para equipos solares.</p>
                    <a href="panorama.php#normativa" class="read-more">Ver normativa →</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── Latest News (dinámico desde artículos) ────────────────── -->
<?php if (!empty($noticias)): ?>
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">Blog científico</span>
            <h2>Últimas publicaciones</h2>
        </div>
        <div class="article-grid">
            <?php foreach ($noticias as $n): ?>
            <article class="card article-card">
                <div class="card-img" style="background:var(--green-light);display:flex;align-items:center;justify-content:center;font-size:3rem;">☀️</div>
                <div class="card-body">
                    <div class="article-meta">
                        <span class="badge" style="background:<?= htmlspecialchars($n['categoria_color']) ?>22;color:<?= htmlspecialchars($n['categoria_color']) ?>">
                            <?= htmlspecialchars($n['categoria_nombre']) ?>
                        </span>
                        <span class="article-date"><?= date('d/m/Y', strtotime($n['creado_en'])) ?></span>
                    </div>
                    <h3 class="article-title">
                        <a href="articulo.php?slug=<?= htmlspecialchars($n['slug']) ?>">
                            <?= htmlspecialchars($n['titulo']) ?>
                        </a>
                    </h3>
                    <p class="article-excerpt"><?= htmlspecialchars(mb_substr($n['resumen'], 0, 130)) ?>…</p>
                    <a href="articulo.php?slug=<?= htmlspecialchars($n['slug']) ?>" class="read-more">Leer artículo →</a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center;margin-top:2rem">
            <a href="noticias.php" class="btn btn-outline-green">Ver todas las publicaciones →</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ── CTA Banner (estático) ─────────────────────────────────── -->
<section class="section" style="background:var(--bg-dark);color:var(--white);text-align:center">
    <div class="container">
        <h2 style="color:var(--white);margin-bottom:.75rem">¿Cuánto podrías ahorrar con paneles solares?</h2>
        <p style="color:rgba(255,255,255,.7);margin-bottom:2rem">Usa nuestra calculadora de ingeniería fotovoltaica: ingresa tu consumo y obtén un informe técnico imprimible en segundos.</p>
        <a href="calculadora.php" class="btn btn-amber" style="font-size:1.05rem;padding:.85rem 2.25rem">Calcular ahorro solar →</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>