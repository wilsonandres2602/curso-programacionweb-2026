<?php
$pageTitle = 'Calculadora Solar';
include 'includes/header.php';
?>

<div class="page-hero">
    <div class="container">
        <nav class="breadcrumb"><a href="index.php">Inicio</a><span>/</span><span>Calculadora Solar</span></nav>
        <h1>Calculadora de Ahorro Fotovoltaico</h1>
        <p>Simulador de ingeniería: estima la potencia pico, número de paneles, inversión, ROI y huella de carbono de tu sistema solar.</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="calc-wrapper">

            <!-- ── Formulario ────────────────────────────── -->
            <div class="calc-panel">
                <h2 style="margin-bottom:1.5rem;font-size:1.25rem">Datos de entrada</h2>
                <form id="calc-form" data-validate novalidate>

                    <div class="form-group">
                        <label class="form-label" for="consumo">Consumo mensual (kWh) <span style="color:#dc3545">*</span></label>
                        <input type="number" id="consumo" name="consumo" class="form-control"
                            placeholder="Ej: 350" min="10" max="50000" required
                            aria-describedby="consumo-error">
                        <span class="form-error" id="consumo-error" role="alert"></span>
                        <small style="color:var(--text-muted);font-size:.8rem">Puedes consultar el dato en tu factura de energía (kWh o kW·h)</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="tipo">Tipo de instalación <span style="color:#dc3545">*</span></label>
                        <select id="tipo" name="tipo" class="form-control" required aria-describedby="tipo-error">
                            <option value="">-- Selecciona --</option>
                            <option value="residencial">Residencial</option>
                            <option value="comercial">Comercial</option>
                            <option value="industrial">Industrial</option>
                        </select>
                        <span class="form-error" id="tipo-error" role="alert"></span>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="region">Región de Colombia <span style="color:#dc3545">*</span></label>
                        <select id="region" name="region" class="form-control" required aria-describedby="region-error">
                            <option value="">-- Selecciona tu región --</option>
                            <option value="caribe">Caribe (La Guajira, Atlántico, Bolívar, Magdalena…)</option>
                            <option value="andina">Andina (Antioquia, Cundinamarca, Valle del Cauca…)</option>
                            <option value="pacifica">Pacífica (Chocó, Nariño…)</option>
                            <option value="orinoquia">Orinoquía (Meta, Casanare, Arauca…)</option>
                            <option value="amazonia">Amazonía (Amazonas, Putumayo, Vaupés…)</option>
                        </select>
                        <span class="form-error" id="region-error" role="alert"></span>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="area">Área de techo disponible (m²) — opcional</label>
                        <input type="number" id="area" name="area" class="form-control"
                            placeholder="Ej: 40" min="0" max="10000">
                        <small style="color:var(--text-muted);font-size:.8rem">Útil para verificar si hay espacio suficiente para los paneles</small>
                    </div>

                    <button type="submit" class="btn btn-amber" style="width:100%;justify-content:center;font-size:1rem;padding:.85rem">
                        ☀️ Calcular ahorro solar
                    </button>
                </form>

                <div style="margin-top:1.5rem;padding:1rem;background:var(--bg-light);border-radius:var(--radius-sm);font-size:.82rem;color:var(--text-muted)">
                    <strong>Metodología:</strong> Cálculo basado en potencia pico (kWp = E_diaria / (GHI × PR)), paneles de 400W, PR=0.8. Tarifas CREG 2024. Incentivos fiscales Ley 1715. Factor de emisión: 0,254 kg CO₂/kWh (UPME).
                </div>
            </div>

            <!-- ── Panel de Resultados ───────────────────── -->
            <div>
                <!-- Estado vacío -->
                <div id="result-empty" class="result-empty">
                    <div class="result-icon">☀️</div>
                    <h3 style="margin-bottom:.5rem">Tus resultados aparecerán aquí</h3>
                    <p>Completa el formulario y presiona <strong>Calcular ahorro solar</strong> para obtener tu estimación fotovoltaica personalizada.</p>
                </div>

                <!-- Resultados (oculto inicialmente) -->
                <div id="result-panel" style="display:none">
                    <div class="result-cards">
                        <div class="result-card r-green">
                            <span class="result-icon-sm">💰</span>
                            <div>
                                <div class="result-val" id="res-ahorro">—</div>
                                <div class="result-label">Ahorro estimado mensual</div>
                            </div>
                        </div>
                        <div class="result-card r-amber">
                            <span class="result-icon-sm">🔆</span>
                            <div>
                                <div class="result-val" id="res-paneles">—</div>
                                <div class="result-label">Sistema recomendado</div>
                            </div>
                        </div>
                        <div class="result-card" style="background:#dbeafe;border-left:4px solid var(--blue)">
                            <span class="result-icon-sm">🌿</span>
                            <div>
                                <div class="result-val" id="res-co2">—</div>
                                <div class="result-label">CO₂ evitado / equivalencia</div>
                            </div>
                        </div>
                        <div class="result-card r-green">
                            <span class="result-icon-sm">🏗️</span>
                            <div>
                                <div class="result-val" id="res-inversion">—</div>
                                <div class="result-label">Inversión estimada</div>
                            </div>
                        </div>
                        <div class="result-card r-amber">
                            <span class="result-icon-sm">📅</span>
                            <div>
                                <div class="result-val" id="res-roi">—</div>
                                <div class="result-label">Retorno de inversión (ROI)</div>
                            </div>
                        </div>
                        <div class="result-card" style="background:#f1f5f9;border-left:4px solid #94a3b8">
                            <span class="result-icon-sm">📐</span>
                            <div>
                                <div class="result-val" id="res-area">—</div>
                                <div class="result-label">Área de instalación requerida</div>
                            </div>
                        </div>
                    </div>

                    <!-- Comparador de factura -->
                    <div class="chart-container" style="margin-bottom:1.5rem">
                        <p class="chart-title">Comparación de factura mensual (COP)</p>
                        <div style="margin-bottom:.75rem">
                            <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:.3rem">
                                <span style="color:var(--text-muted)">Factura actual</span>
                                <strong id="lbl-actual">—</strong>
                            </div>
                            <div style="background:var(--border);border-radius:4px;height:18px;overflow:hidden">
                                <div id="bar-actual" style="height:100%;background:#dc3545;border-radius:4px;transition:width .8s ease;width:0"></div>
                            </div>
                        </div>
                        <div>
                            <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:.3rem">
                                <span style="color:var(--text-muted)">Con energía solar</span>
                                <strong id="lbl-nueva" style="color:var(--green)">—</strong>
                            </div>
                            <div style="background:var(--border);border-radius:4px;height:18px;overflow:hidden">
                                <div id="bar-nueva" style="height:100%;background:var(--green);border-radius:4px;transition:width .8s ease;width:0"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráfica proyección 10 años -->
                    <div class="chart-container" style="margin-bottom:1.5rem">
                        <p class="chart-title">Ahorro acumulado proyectado a 10 años (inflación tarifaria 8% anual)</p>
                        <svg id="projection-chart" width="100%" viewBox="0 0 580 220" data-monthly="0" role="img" aria-label="Proyección de ahorro acumulado a 10 años"></svg>
                    </div>
                </div>

                <!-- Informe imprimible -->
                <div id="report-section" style="display:none"></div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>