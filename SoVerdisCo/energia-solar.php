<?php
require_once 'includes/db.php';
$pageTitle = 'Energía Solar';

// Obtener contenido dinámico
$stmt = $conn->prepare("SELECT titulo, contenido FROM contenido_paginas WHERE pagina = 'energia-solar'");
$stmt->execute();
$energia = $stmt->get_result()->fetch_assoc();
if (!$energia) {
    $energia = [
        'titulo'    => 'Energía Solar en Colombia',
        'contenido' => '<p>Comprende cómo funciona la energía fotovoltaica, su potencial en nuestro territorio y las configuraciones de instalación disponibles.</p>'
    ];
}

include 'includes/header.php';
?>

<div class="page-hero">
    <div class="container">
        <nav class="breadcrumb" aria-label="Ruta de navegación">
            <a href="index.php">Inicio</a><span>/</span><span>Energía Solar</span>
        </nav>
        <h1><?= htmlspecialchars($energia['titulo']) ?></h1>
        <?= $energia['contenido'] ?>
    </div>
</div>

<!-- ── Flowchart (estático) ──────────────────────────────────── -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">Simulador de conversión energética</span>
            <h2>¿Cómo se genera electricidad a partir del sol?</h2>
            <p>Haz clic en cada etapa del proceso para explorar la física detrás de la generación fotovoltaica.</p>
        </div>

        <div class="flowchart-wrap" role="region" aria-label="Flujograma de conversión solar">
            <div style="display:flex;align-items:center;justify-content:center;flex-wrap:wrap;gap:.5rem">
                <div class="flow-stage" data-stage="radiacion" role="button" tabindex="0" aria-pressed="false">
                    <span class="stage-icon">☀️</span>
                    <span class="stage-label">Radiación solar</span>
                </div>
                <span class="flow-arrow" aria-hidden="true">→</span>
                <div class="flow-stage" data-stage="absorcion" role="button" tabindex="0" aria-pressed="false">
                    <span class="stage-icon">🔬</span>
                    <span class="stage-label">Absorción en silicio</span>
                </div>
                <span class="flow-arrow" aria-hidden="true">→</span>
                <div class="flow-stage" data-stage="inversion" role="button" tabindex="0" aria-pressed="false">
                    <span class="stage-icon">⚡</span>
                    <span class="stage-label">Inversión DC→AC</span>
                </div>
                <span class="flow-arrow" aria-hidden="true">→</span>
                <div class="flow-stage" data-stage="autoconsumo" role="button" tabindex="0" aria-pressed="false">
                    <span class="stage-icon">🏠</span>
                    <span class="stage-label">Autoconsumo</span>
                </div>
                <span class="flow-arrow" aria-hidden="true">→</span>
                <div class="flow-stage" data-stage="inyeccion" role="button" tabindex="0" aria-pressed="false">
                    <span class="stage-icon">🔄</span>
                    <span class="stage-label">Inyección bidireccional</span>
                </div>
            </div>

            <div id="stage-detail" class="stage-detail" role="status" aria-live="polite">
                <h4>Selecciona una etapa</h4>
                <p>Haz clic en cualquier paso del flujograma para ver su descripción técnica.</p>
            </div>
        </div>
    </div>
</section>

<!-- ── Potential (estático) ─────────────────────────────────── -->
<section class="section section-alt">
    <div class="container">
        <div class="grid-2" style="align-items:center;gap:3rem">
            <div>
                <span class="eyebrow" style="color:var(--green)">Potencial colombiano</span>
                <h2>Un país hecho para la energía solar</h2>
                <p>Colombia se ubica entre los 0° y 12° de latitud norte, lo que garantiza ángulos de incidencia solar favorables durante todo el año, sin las variaciones estacionales extremas que afectan a países de latitudes medias.</p>
                <ul style="margin:1.25rem 0;display:flex;flex-direction:column;gap:.6rem">
                    <li style="display:flex;gap:.6rem"><span style="color:var(--green);font-weight:700">✓</span> Radiación media nacional de <strong>4,5 kWh/m²/día</strong></li>
                    <li style="display:flex;gap:.6rem"><span style="color:var(--green);font-weight:700">✓</span> Pico en La Guajira: <strong>5,8 kWh/m²/día</strong></li>
                    <li style="display:flex;gap:.6rem"><span style="color:var(--green);font-weight:700">✓</span> Potencial técnico-económico: <strong>&gt;8.000 GW</strong></li>
                    <li style="display:flex;gap:.6rem"><span style="color:var(--green);font-weight:700">✓</span> Crecimiento del <strong>187%</strong> en capacidad instalada en 2024</li>
                    <li style="display:flex;gap:.6rem"><span style="color:var(--green);font-weight:700">✓</span> <strong>13,5 GW</strong> en proyectos aprobados por la UPME para 2033</li>
                </ul>
                <a href="panorama.php" class="btn btn-primary">Ver panorama nacional →</a>
            </div>
            <div>
                <svg viewBox="0 0 320 380" role="img" aria-label="Mapa de radiación solar en Colombia">
                    <title>Radiación solar por región en Colombia</title>
                    <path d="M100 20 L220 30 L260 80 L280 140 L260 200 L240 260 L200 320 L160 360 L130 340 L80 300 L60 240 L40 180 L50 120 L80 60 Z" fill="#E8F5EE" stroke="#1B8A5A" stroke-width="2"/>
                    <ellipse cx="200" cy="60"  rx="50" ry="35" fill="#F5A623" opacity=".35"/>
                    <text x="200" y="58"  text-anchor="middle" font-size="10" font-weight="700" fill="#7a4f00">La Guajira</text>
                    <text x="200" y="70"  text-anchor="middle" font-size="9"  fill="#7a4f00">5.8 kWh/m²</text>
                    <ellipse cx="190" cy="120" rx="40" ry="30" fill="#F5A623" opacity=".25"/>
                    <text x="190" y="118" text-anchor="middle" font-size="10" font-weight="700" fill="#7a4f00">Atlántico</text>
                    <text x="190" y="130" text-anchor="middle" font-size="9"  fill="#7a4f00">5.2 kWh/m²</text>
                    <ellipse cx="160" cy="190" rx="45" ry="30" fill="#1B8A5A" opacity=".2"/>
                    <text x="160" y="188" text-anchor="middle" font-size="10" font-weight="700" fill="#0F5C3C">Andina</text>
                    <text x="160" y="200" text-anchor="middle" font-size="9"  fill="#0F5C3C">4.2 kWh/m²</text>
                    <ellipse cx="210" cy="240" rx="40" ry="25" fill="#F5A623" opacity=".2"/>
                    <text x="210" y="238" text-anchor="middle" font-size="10" font-weight="700" fill="#7a4f00">Orinoquía</text>
                    <text x="210" y="250" text-anchor="middle" font-size="9"  fill="#7a4f00">4.9 kWh/m²</text>
                    <rect x="30" y="330" width="12" height="12" rx="2" fill="#F5A623" opacity=".7"/>
                    <text x="46" y="341" font-size="10" fill="#555">&gt;4.8 kWh/m²</text>
                    <rect x="130" y="330" width="12" height="12" rx="2" fill="#1B8A5A" opacity=".5"/>
                    <text x="146" y="341" font-size="10" fill="#555">4.0–4.8 kWh/m²</text>
                </svg>
            </div>
        </div>
    </div>
</section>

<!-- ── System Configs (estático) ─────────────────────────────── -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">Tipos de instalación</span>
            <h2>Configuraciones comunes de sistemas solares</h2>
            <p>Cada configuración tiene ventajas de costo y autonomía según las necesidades del usuario.</p>
        </div>
        <div class="config-cards">
            <div class="config-card on-grid">
                <div class="config-header">
                    <span class="icon">🔌</span>
                    <div><h3>On-Grid</h3><small style="color:var(--text-muted)">Conectado a la red</small></div>
                </div>
                <div class="config-body">
                    <div class="config-row"><span class="key">Costo relativo</span><span class="val">Bajo</span></div>
                    <div class="config-row"><span class="key">Autonomía</span><span class="val">Sin batería</span></div>
                    <div class="config-row"><span class="key">Excedentes</span><span class="val">Inyecta a la red</span></div>
                    <div class="config-row"><span class="key">Ideal para</span><span class="val">Residencial / comercial</span></div>
                    <div class="config-row"><span class="key">Normativa</span><span class="val">CREG 174/2021</span></div>
                    <p style="font-size:.85rem;color:var(--text-muted);margin-top:.75rem">El sistema más difundido en Colombia. Los excedentes generan créditos en la factura mediante medición neta (net metering).</p>
                </div>
            </div>
            <div class="config-card off-grid">
                <div class="config-header">
                    <span class="icon">🔋</span>
                    <div><h3>Off-Grid</h3><small style="color:var(--text-muted)">Sistema aislado</small></div>
                </div>
                <div class="config-body">
                    <div class="config-row"><span class="key">Costo relativo</span><span class="val">Alto</span></div>
                    <div class="config-row"><span class="key">Autonomía</span><span class="val">Total (baterías)</span></div>
                    <div class="config-row"><span class="key">Excedentes</span><span class="val">Almacena en batería</span></div>
                    <div class="config-row"><span class="key">Ideal para</span><span class="val">ZNI / zonas rurales</span></div>
                    <div class="config-row"><span class="key">Tecnología</span><span class="val">LFP / AGM</span></div>
                    <p style="font-size:.85rem;color:var(--text-muted);margin-top:.75rem">Fundamental para las <strong>Zonas No Interconectadas (ZNI)</strong> de Colombia, que albergan comunidades sin acceso al SIN.</p>
                </div>
            </div>
            <div class="config-card hybrid">
                <div class="config-header">
                    <span class="icon">⚡</span>
                    <div><h3>Híbrido</h3><small style="color:var(--text-muted)">Red + batería</small></div>
                </div>
                <div class="config-body">
                    <div class="config-row"><span class="key">Costo relativo</span><span class="val">Medio-alto</span></div>
                    <div class="config-row"><span class="key">Autonomía</span><span class="val">Parcial (respaldo)</span></div>
                    <div class="config-row"><span class="key">Excedentes</span><span class="val">Batería → Red</span></div>
                    <div class="config-row"><span class="key">Ideal para</span><span class="val">Zonas con cortes</span></div>
                    <div class="config-row"><span class="key">Tecnología</span><span class="val">LFP + inversor híbrido</span></div>
                    <p style="font-size:.85rem;color:var(--text-muted);margin-top:.75rem">Solución óptima para usuarios que quieren independencia energética parcial y mantener conexión a la red como respaldo.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>