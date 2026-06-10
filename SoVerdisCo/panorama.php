<?php
require_once 'includes/db.php';
$pageTitle = 'Panorama Solar';

// Proyectos con filtros opcionales
$filtroEstado  = $_GET['estado']  ?? 'todos';
$filtroRegion  = $_GET['region']  ?? 'todos';
$busqueda      = trim($_GET['q']  ?? '');

$where = ['1=1'];
$params = []; $types = '';
if ($filtroEstado !== 'todos') { $where[] = 'estado = ?'; $params[] = $filtroEstado; $types .= 's'; }
if ($filtroRegion !== 'todos') { $where[] = 'region = ?'; $params[] = $filtroRegion;  $types .= 's'; }
if ($busqueda !== '') { $where[] = '(nombre LIKE ? OR departamento LIKE ? OR municipio LIKE ?)'; $like = "%$busqueda%"; $params[] = $like; $params[] = $like; $params[] = $like; $types .= 'sss'; }

$sql = 'SELECT * FROM proyectos WHERE ' . implode(' AND ', $where) . ' ORDER BY capacidad_mw DESC';
$stmt = $conn->prepare($sql);
if ($params) { $stmt->bind_param($types, ...$params); }
$stmt->execute();
$proyectos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Stats
$totalMW = $conn->query("SELECT SUM(capacidad_mw) FROM proyectos WHERE estado='operacion'")->fetch_row()[0] ?? 0;
$totalProyectos = $conn->query("SELECT COUNT(*) FROM proyectos")->fetch_row()[0] ?? 0;
?>
<?php include 'includes/header.php'; ?>

<div class="page-hero">
    <div class="container">
        <nav class="breadcrumb" aria-label="Ruta de navegación">
            <a href="index.php">Inicio</a><span>/</span><span>Panorama Colombia</span>
        </nav>
        <h1>Panorama Solar en Colombia</h1>
        <p>Serie histórica de capacidad instalada, mega-proyectos activos y marco normativo del sector fotovoltaico colombiano.</p>
    </div>
</div>

<!-- ── Growth Chart ───────────────────────────────────────── -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">Serie histórica</span>
            <h2>Crecimiento de capacidad solar instalada en Colombia</h2>
            <p>Pasa el cursor sobre los puntos del gráfico para ver los valores exactos por año.</p>
        </div>
        <div class="chart-container" style="position:relative">
            <p class="chart-title">Capacidad fotovoltaica acumulada (MW) — 2017 a 2025 · Fuente: UPME / SER Colombia</p>
            <svg id="growth-chart" width="100%" viewBox="0 0 680 300" role="img" aria-label="Gráfica de crecimiento de energía solar en Colombia"></svg>
            <div id="chart-tooltip" class="tooltip-box" style="position:fixed"></div>
        </div>
    </div>
</section>

<!-- ── Projects Filter ────────────────────────────────────── -->
<section class="section section-alt" id="proyectos">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">Parques solares</span>
            <h2>Mega-proyectos fotovoltaicos</h2>
        </div>

        <form method="GET" action="panorama.php#proyectos" style="display:flex;flex-wrap:wrap;gap:1rem;margin-bottom:2rem;align-items:flex-end">
            <div style="flex:1;min-width:200px">
                <label class="form-label" for="q">Buscar proyecto</label>
                <input type="text" id="q" name="q" class="form-control" placeholder="Nombre, departamento, municipio..." value="<?= htmlspecialchars($busqueda) ?>">
            </div>
            <div>
                <label class="form-label" for="estado">Estado</label>
                <select id="estado" name="estado" class="form-control">
                    <option value="todos" <?= $filtroEstado==='todos'?'selected':'' ?>>Todos los estados</option>
                    <option value="operacion"    <?= $filtroEstado==='operacion'?'selected':'' ?>>En operación</option>
                    <option value="pruebas"      <?= $filtroEstado==='pruebas'?'selected':'' ?>>En pruebas</option>
                    <option value="construccion" <?= $filtroEstado==='construccion'?'selected':'' ?>>En construcción</option>
                    <option value="aprobado"     <?= $filtroEstado==='aprobado'?'selected':'' ?>>Aprobado</option>
                </select>
            </div>
            <div>
                <label class="form-label" for="region">Región</label>
                <select id="region" name="region" class="form-control">
                    <option value="todos"    <?= $filtroRegion==='todos'?'selected':'' ?>>Todas las regiones</option>
                    <option value="Caribe"   <?= $filtroRegion==='Caribe'?'selected':'' ?>>Caribe</option>
                    <option value="Andina"   <?= $filtroRegion==='Andina'?'selected':'' ?>>Andina</option>
                    <option value="Orinoquía"<?= $filtroRegion==='Orinoquía'?'selected':'' ?>>Orinoquía</option>
                    <option value="Pacífica" <?= $filtroRegion==='Pacífica'?'selected':'' ?>>Pacífica</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <?php if ($filtroEstado!=='todos' || $filtroRegion!=='todos' || $busqueda): ?>
            <a href="panorama.php#proyectos" class="btn btn-outline-green">Limpiar filtros</a>
            <?php endif; ?>
        </form>

        <?php if (empty($proyectos)): ?>
        <div style="text-align:center;padding:3rem;color:var(--text-muted)">
            <p style="font-size:2rem">🔍</p>
            <p>No se encontraron proyectos con los filtros seleccionados.</p>
        </div>
        <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Proyecto</th>
                        <th>Capacidad</th>
                        <th>Región / Depto.</th>
                        <th>Municipio</th>
                        <th>Empresa</th>
                        <th>Estado</th>
                        <th>Año</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($proyectos as $p): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
                        <td><strong style="color:var(--amber)"><?= number_format($p['capacidad_mw'], 0, ',', '.') ?> MW</strong></td>
                        <td><?= htmlspecialchars($p['region']) ?> / <?= htmlspecialchars($p['departamento']) ?></td>
                        <td><?= htmlspecialchars($p['municipio']) ?></td>
                        <td><?= htmlspecialchars($p['empresa']) ?></td>
                        <td>
                            <span class="badge estado-<?= $p['estado'] ?>">
                                <?= ucfirst(str_replace(['operacion','pruebas','construccion','aprobado'],['En operación','En pruebas','Construcción','Aprobado'], $p['estado'])) ?>
                            </span>
                        </td>
                        <td><?= $p['anio_inicio'] ?? '—' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p style="font-size:.82rem;color:var(--text-muted);margin-top:.75rem">Mostrando <?= count($proyectos) ?> de <?= $totalProyectos ?> proyectos · Fuente: UPME, SER Colombia 2025</p>
        <?php endif; ?>
    </div>
</section>

<!-- ── Legal Timeline ─────────────────────────────────────── -->
<section class="section" id="normativa">
    <div class="container">
        <div class="grid-2" style="gap:3rem;align-items:start">
            <div>
                <span class="eyebrow" style="color:var(--green)">Marco normativo</span>
                <h2>Línea de tiempo regulatoria</h2>
                <p style="color:var(--text-muted);margin-bottom:2rem">Los instrumentos legales que han configurado el ecosistema de la energía solar en Colombia.</p>
                <div class="timeline" role="list">
                    <div class="timeline-item" role="listitem">
                        <div class="timeline-dot">2014</div>
                        <div class="timeline-year">Mayo 2014</div>
                        <div class="timeline-title">Ley 1715 — Marco base FNCER</div>
                        <div class="timeline-desc">Primer instrumento para el desarrollo y promoción de fuentes no convencionales de energía renovable. Introduce incentivos tributarios: 50% deducción en renta, exención de IVA y arancel para equipos de generación solar.</div>
                    </div>
                    <div class="timeline-item" role="listitem">
                        <div class="timeline-dot">2018</div>
                        <div class="timeline-year">Marzo 2018</div>
                        <div class="timeline-title">Resolución CREG 030 — Autogeneración</div>
                        <div class="timeline-desc">Regula la autogeneración a gran escala y establece las condiciones para la conexión de grandes productores fotovoltaicos al Sistema Interconectado Nacional (SIN).</div>
                    </div>
                    <div class="timeline-item" role="listitem">
                        <div class="timeline-dot">2021</div>
                        <div class="timeline-year">Julio 2021</div>
                        <div class="timeline-title">CREG 174 — Medición neta (Net Metering)</div>
                        <div class="timeline-desc">Regula el esquema de excedentes de energía para generación distribuida pequeña escala. Los usuarios generadores reciben créditos en factura por la energía solar inyectada a la red del distribuidor.</div>
                    </div>
                    <div class="timeline-item" role="listitem">
                        <div class="timeline-dot">2021</div>
                        <div class="timeline-year">Octubre 2021</div>
                        <div class="timeline-title">Ley 2099 — Transición energética</div>
                        <div class="timeline-desc">Amplía el marco de la Ley 1715, establece metas concretas de transición energética, elimina barreras administrativas y crea el fondo de transición energética justa.</div>
                    </div>
                    <div class="timeline-item" role="listitem">
                        <div class="timeline-dot">2024</div>
                        <div class="timeline-year">2024–2026</div>
                        <div class="timeline-title">Programa Colombia Solar</div>
                        <div class="timeline-desc">Iniciativa presidencial con meta de financiación de USD 10.000 millones para llevar energía solar fotovoltaica a hogares de estratos 1 y 2 en todo el territorio nacional.</div>
                    </div>
                </div>
            </div>
            <div>
                <span class="eyebrow" style="color:var(--green)">Resumen estadístico</span>
                <h2>Colombia en cifras solares</h2>
                <div style="display:flex;flex-direction:column;gap:1rem;margin-top:1.5rem">
                    <?php
                    $stats = [
                        ['☀️', '1.594 MW', 'Capacidad fotovoltaica instalada al cierre 2025'],
                        ['📈', '+187%', 'Crecimiento de capacidad instalada en 2024'],
                        ['🏭', '98 proyectos', 'En operación o pruebas iniciales'],
                        ['🌍', '7,6%', 'Participación en la matriz eléctrica nacional'],
                        ['💡', '4,5 kWh/m²/día', 'Radiación horizontal global promedio (IDEAM)'],
                        ['🎯', '2.297 MW', 'Meta PND 2022–2026 (avance 80%)'],
                    ];
                    foreach ($stats as [$icon, $val, $label]):
                    ?>
                    <div style="background:var(--white);border-radius:var(--radius-sm);padding:1rem 1.25rem;box-shadow:var(--shadow);display:flex;align-items:center;gap:1rem">
                        <span style="font-size:1.5rem"><?= $icon ?></span>
                        <div>
                            <div style="font-size:1.25rem;font-weight:700;color:var(--amber)"><?= $val ?></div>
                            <div style="font-size:.82rem;color:var(--text-muted)"><?= $label ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <p style="font-size:.78rem;color:var(--text-muted);margin-top:1rem">Fuentes: UPME, SER Colombia, PV Magazine Latinoamérica · Datos 2025</p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>