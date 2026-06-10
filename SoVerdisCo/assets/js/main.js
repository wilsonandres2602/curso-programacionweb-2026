/* ============================================================
   SoVerdisCo — main.js
   Validaciones, interactividad, filtros — sin frameworks
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

    /* ── Hamburger menu ──────────────────────────────────── */
    const toggle = document.querySelector('.nav-toggle');
    const menu   = document.querySelector('.nav-menu');
    if (toggle && menu) {
        toggle.addEventListener('click', () => {
            const open = menu.classList.toggle('open');
            toggle.setAttribute('aria-expanded', open);
            toggle.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
        });
    }

    /* ── Eliminado el bloque que causaba el bucle infinito ── */
    /* Los filtros de noticias.php son enlaces normales que recargan la página.
       No se debe hacer .click() automático. */

    /* ── Live search (noticias.php) sin recargar ─────────── */
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const q = this.value.toLowerCase().trim();
            const articles = document.querySelectorAll('.article-card');
            let hasVisible = false;
            articles.forEach(card => {
                const text = card.textContent.toLowerCase();
                const matches = text.includes(q);
                card.style.display = matches ? '' : 'none';
                if (matches) hasVisible = true;
            });
            // Mostrar mensaje si no hay resultados
            let noResultsMsg = document.getElementById('live-search-no-results');
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('div');
                noResultsMsg.id = 'live-search-no-results';
                noResultsMsg.style.textAlign = 'center';
                noResultsMsg.style.padding = '2rem';
                noResultsMsg.style.color = 'var(--text-muted)';
                const grid = document.querySelector('.article-grid');
                if (grid) grid.parentNode.insertBefore(noResultsMsg, grid.nextSibling);
            }
            noResultsMsg.style.display = hasVisible ? 'none' : 'block';
            if (!hasVisible && q !== '') {
                noResultsMsg.innerHTML = '<p>🔍 No se encontraron artículos que coincidan con "<strong>' + escapeHtml(q) + '</strong>".</p><p>Prueba con otras palabras o <a href="noticias.php">limpia la búsqueda</a>.</p>';
            } else if (q === '') {
                noResultsMsg.style.display = 'none';
            }
        });
    }

    /* ── Flowchart stages (energia-solar) ────────────────── */
    const stages = document.querySelectorAll('.flow-stage');
    const detail = document.getElementById('stage-detail');
    if (stages.length && detail) {
        const info = {
            radiacion: {
                title: '☀️ Radiación solar incidente',
                text:  'La luz solar (fotones) impacta la superficie del panel fotovoltaico. Colombia recibe en promedio 4,5 kWh/m²/día, con picos de 5,8 kWh/m²/día en La Guajira, cifra muy superior a la media europea de 3,1 kWh/m²/día.'
            },
            absorcion: {
                title: '🔬 Absorción en silicio',
                text:  'Las celdas de silicio cristalino absorben los fotones. Cuando un fotón con energía suficiente (>1,12 eV) impacta un átomo de silicio, libera un electrón — efecto fotovoltaico. Las celdas monocristalinas actuales logran eficiencias de hasta 22%.'
            },
            inversion: {
                title: '⚡ Inversión de corriente DC → AC',
                text:  'La corriente continua (DC) generada por los paneles es convertida a corriente alterna (AC, 60 Hz en Colombia) por el inversor fotovoltaico. El inversor también realiza el seguimiento del punto de máxima potencia (MPPT) y protecciones anti-isla.'
            },
            autoconsumo: {
                title: '🏠 Autoconsumo',
                text:  'La energía AC generada alimenta directamente las cargas del inmueble — electrodomésticos, iluminación, equipos — reduciendo el consumo de la red. En sistemas residenciales colombianos, un sistema de 6 kWp puede cubrir entre el 60% y el 90% del consumo mensual típico.'
            },
            inyeccion: {
                title: '🔄 Inyección bidireccional a la red',
                text:  'Los excedentes de generación (cuando se produce más de lo que se consume) se inyectan a la red eléctrica del distribuidor local. La Resolución CREG 174 de 2021 regula la medición neta (net metering) que permite al usuario recibir créditos en factura por la energía exportada.'
            }
        };
        stages.forEach(s => {
            s.addEventListener('click', () => {
                stages.forEach(x => x.classList.remove('active'));
                s.classList.add('active');
                const d = info[s.dataset.stage];
                if (d) {
                    detail.querySelector('h4').textContent = d.title;
                    detail.querySelector('p').textContent  = d.text;
                    detail.classList.add('visible');
                    detail.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            });
        });
        stages[0]?.click();
    }

    /* ── SVG Chart: growth curve (panorama) ─────────────── */
    const chartSvg = document.getElementById('growth-chart');
    if (chartSvg) buildGrowthChart(chartSvg);

    /* ── SVG Chart: 10-year projection (calculadora) ─────── */
    const projSvg = document.getElementById('projection-chart');
    if (projSvg) {
        const savings = parseFloat(projSvg.dataset.monthly || 0);
        if (savings > 0) buildProjectionChart(projSvg, savings);
    }

    /* ── Calculator logic ────────────────────────────────── */
    const calcForm = document.getElementById('calc-form');
    if (calcForm) {
        calcForm.addEventListener('submit', e => {
            e.preventDefault();
            if (!validateCalcForm()) return;
            computeResults();
        });
    }

    /* ── Admin: confirm delete ────────────────────────────── */
    document.querySelectorAll('.confirm-delete').forEach(btn => {
        btn.addEventListener('click', e => {
            if (!confirm('¿Eliminar este artículo? Esta acción no se puede deshacer.')) {
                e.preventDefault();
            }
        });
    });

    /* ── Form validation (generic) ─────────────────────────── */
    document.querySelectorAll('form[data-validate]').forEach(form => {
        form.addEventListener('submit', e => {
            if (!validateForm(form)) e.preventDefault();
        });
        form.querySelectorAll('[required]').forEach(field => {
            field.addEventListener('blur', () => validateField(field));
        });
    });

    /* ── Print report ────────────────────────────────────── */
    const printBtn = document.getElementById('print-report');
    if (printBtn) printBtn.addEventListener('click', () => window.print());

});

/* ── Validation helpers ────────────────────────────────────── */
function validateForm(form) {
    let valid = true;
    form.querySelectorAll('[required]').forEach(f => { if (!validateField(f)) valid = false; });
    return valid;
}
function validateField(field) {
    const err = field.parentElement.querySelector('.form-error');
    let msg = '';
    if (!field.value.trim()) msg = 'Este campo es obligatorio.';
    else if (field.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value)) msg = 'Ingresa un correo válido.';
    else if (field.type === 'number') {
        const v = parseFloat(field.value);
        if (isNaN(v) || v < (parseFloat(field.min)||0)) msg = `El valor mínimo es ${field.min||0}.`;
    }
    field.classList.toggle('error', !!msg);
    if (err) { err.textContent = msg; err.classList.toggle('visible', !!msg); }
    return !msg;
}

function validateCalcForm() {
    const fields = ['consumo', 'tipo', 'region'];
    return fields.map(id => validateField(document.getElementById(id))).every(Boolean);
}

/* ── Calculator computation ────────────────────────────────── */
function computeResults() {
    const consumo  = parseFloat(document.getElementById('consumo').value);
    const tipo     = document.getElementById('tipo').value;
    const region   = document.getElementById('region').value;
    const area     = parseFloat(document.getElementById('area').value) || 0;

    // GHI diario por región (kWh/m²/día) — IDEAM 2024
    const ghiMap = { caribe: 5.2, andina: 4.2, pacifica: 3.8, orinoquia: 4.9, amazonia: 4.1 };
    const tarifaMap = { caribe: 720, andina: 680, pacifica: 660, orinoquia: 710, amazonia: 640 }; // COP/kWh aprox.
    const ghi    = ghiMap[region]   || 4.5;
    const tarifa = tarifaMap[region] || 680;

    // Potencia pico requerida (kWp)
    // P = E_diaria / (GHI * PR), PR = 0.8 (factor rendimiento)
    const PR = 0.8;
    const potencia_kWp = (consumo / 30) / (ghi * PR);

    // Número de paneles (400W c/u)
    const paneles = Math.ceil(potencia_kWp * 1000 / 400);

    // Área requerida (2m² por panel aprox.)
    const area_req = paneles * 2;

    // Ahorro mensual
    const generacion_mensual = potencia_kWp * ghi * PR * 30;
    const cubierto = Math.min(generacion_mensual / consumo, 1);
    const ahorro_kwh = generacion_mensual < consumo ? generacion_mensual : consumo;
    const ahorro_cop = Math.round(ahorro_kwh * tarifa);

    // Inversión estimada (USD 800/kWp, TRM 4200)
    const inversion_usd = Math.round(potencia_kWp * 800);
    const inversion_cop = inversion_usd * 4200;

    // ROI con Ley 1715 (50% deducción renta)
    const inversion_neta = inversion_cop * 0.5;
    const roi_meses = Math.round(inversion_neta / ahorro_cop);

    // CO₂ evitado (0.254 kg CO₂/kWh — factor Colombia)
    const co2_kg = Math.round(ahorro_kwh * 0.254 * 12);
    const arboles = Math.round(co2_kg / 21.7); // 1 árbol absorbe ~21.7 kg CO₂/año

    // Render results
    document.getElementById('res-ahorro').textContent  = `$${ahorro_cop.toLocaleString('es-CO')} COP/mes`;
    document.getElementById('res-paneles').textContent  = `${paneles} paneles de 400W (${potencia_kWp.toFixed(1)} kWp)`;
    document.getElementById('res-co2').textContent      = `${co2_kg.toLocaleString('es-CO')} kg CO₂/año ≈ ${arboles} árboles`;
    document.getElementById('res-inversion').textContent= `~$${inversion_cop.toLocaleString('es-CO')} COP`;
    document.getElementById('res-roi').textContent      = `${Math.floor(roi_meses/12)} años ${roi_meses%12} meses (con Ley 1715)`;
    document.getElementById('res-area').textContent     = `${area_req} m² requeridos`;

    // Bar comparison
    const facturaActual = Math.round(consumo * tarifa);
    const facturaNueva  = Math.round(Math.max(consumo - ahorro_kwh, 0) * tarifa);
    document.getElementById('bar-actual').style.width   = '100%';
    document.getElementById('bar-nueva').style.width    = `${Math.round((facturaNueva/facturaActual)*100)}%`;
    document.getElementById('lbl-actual').textContent   = `$${facturaActual.toLocaleString('es-CO')} COP`;
    document.getElementById('lbl-nueva').textContent    = `$${facturaNueva.toLocaleString('es-CO')} COP`;

    document.getElementById('result-panel').style.display = 'block';
    document.getElementById('result-empty').style.display = 'none';

    // Projection chart
    const projSvg = document.getElementById('projection-chart');
    if (projSvg) { projSvg.dataset.monthly = ahorro_cop; buildProjectionChart(projSvg, ahorro_cop); }

    // Fill report data
    fillReport({ consumo, tipo, region, potencia_kWp, paneles, area_req, ahorro_cop, inversion_cop, roi_meses, co2_kg, arboles, tarifa, ghi });

    document.getElementById('result-panel').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

/* ── SVG growth chart ──────────────────────────────────────── */
function buildGrowthChart(svg) {
    const data = [
        { year: 2017, mw: 12 }, { year: 2018, mw: 28 }, { year: 2019, mw: 65 },
        { year: 2020, mw: 120 }, { year: 2021, mw: 202 }, { year: 2022, mw: 424 },
        { year: 2023, mw: 754 }, { year: 2024, mw: 1406 }, { year: 2025, mw: 1594 }
    ];
    const W = 640, H = 280, padL = 60, padB = 40, padT = 20, padR = 20;
    const maxMW = 1800;
    const xStep = (W - padL - padR) / (data.length - 1);
    const yScale = v => padT + (H - padT - padB) * (1 - v / maxMW);
    const tooltip = document.getElementById('chart-tooltip');

    const pts = data.map((d, i) => `${padL + i * xStep},${yScale(d.mw)}`).join(' ');

    // Area fill
    const area = `M${padL},${H - padB} ` + data.map((d, i) => `L${padL + i * xStep},${yScale(d.mw)}`).join(' ') + ` L${padL + (data.length-1)*xStep},${H-padB} Z`;

    // Y-axis labels
    let yLabels = '';
    [0, 500, 1000, 1500].forEach(v => {
        const y = yScale(v);
        yLabels += `<line x1="${padL}" y1="${y}" x2="${W - padR}" y2="${y}" stroke="#e5e7eb" stroke-width="1"/>`;
        yLabels += `<text x="${padL - 8}" y="${y + 4}" text-anchor="end" font-size="11" fill="#6b7280">${v}</text>`;
    });

    // X-axis labels
    let xLabels = '';
    data.forEach((d, i) => {
        xLabels += `<text x="${padL + i * xStep}" y="${H - padB + 18}" text-anchor="middle" font-size="11" fill="#6b7280">${d.year}</text>`;
    });

    // Dots with hover
    let dots = '';
    data.forEach((d, i) => {
        const cx = padL + i * xStep, cy = yScale(d.mw);
        dots += `<circle class="chart-dot" cx="${cx}" cy="${cy}" r="5" fill="#F5A623" stroke="#fff" stroke-width="2" 
            data-year="${d.year}" data-mw="${d.mw}" style="cursor:pointer"/>`;
    });

    svg.innerHTML = `
        <defs>
            <linearGradient id="areaGrad" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#F5A623" stop-opacity="0.3"/>
                <stop offset="100%" stop-color="#F5A623" stop-opacity="0.02"/>
            </linearGradient>
        </defs>
        ${yLabels}
        <path d="${area}" fill="url(#areaGrad)"/>
        <polyline points="${pts}" fill="none" stroke="#1B8A5A" stroke-width="2.5" stroke-linejoin="round"/>
        ${dots}
        ${xLabels}
        <text x="${padL - 40}" y="${H/2}" transform="rotate(-90,${padL-40},${H/2})" text-anchor="middle" font-size="11" fill="#6b7280">MW instalados</text>
    `;

    svg.querySelectorAll('.chart-dot').forEach(dot => {
        dot.addEventListener('mouseenter', e => {
            if (tooltip) {
                tooltip.textContent = `${dot.dataset.year}: ${parseFloat(dot.dataset.mw).toLocaleString('es-CO')} MW`;
                tooltip.style.opacity = 1;
            }
        });
        dot.addEventListener('mousemove', e => {
            if (tooltip) {
                tooltip.style.left = (e.pageX + 14) + 'px';
                tooltip.style.top  = (e.pageY - 32) + 'px';
            }
        });
        dot.addEventListener('mouseleave', () => { if (tooltip) tooltip.style.opacity = 0; });
    });
}

/* ── SVG 10-year projection bars ────────────────────────────── */
function buildProjectionChart(svg, monthlyBase) {
    const W = 580, H = 220, padL = 70, padB = 40, padT = 15, padR = 20;
    const inflation = 0.08; // 8% anual en tarifa
    let bars = '', labels = '', maxAcc = 0;
    const data = [];
    let acc = 0;
    for (let y = 1; y <= 10; y++) {
        const m = monthlyBase * Math.pow(1 + inflation, y - 1);
        acc += m * 12;
        data.push({ y, acc });
        if (acc > maxAcc) maxAcc = acc;
    }
    const bW = (W - padL - padR) / 10 * 0.65;
    const bGap = (W - padL - padR) / 10;
    data.forEach((d, i) => {
        const bH = ((H - padT - padB) * d.acc / maxAcc);
        const x = padL + i * bGap;
        const y = H - padB - bH;
        bars   += `<rect x="${x}" y="${y}" width="${bW}" height="${bH}" fill="#1B8A5A" rx="3" opacity="${0.55 + i*0.05}"/>`;
        labels += `<text x="${x + bW/2}" y="${H - padB + 16}" text-anchor="middle" font-size="10" fill="#6b7280">${d.y}a</text>`;
        bars   += `<text x="${x + bW/2}" y="${y - 5}" text-anchor="middle" font-size="9" fill="#1B8A5A">$${(d.acc/1e6).toFixed(1)}M</text>`;
    });
    // Y gridlines
    let grid = '';
    [0, 0.25, 0.5, 0.75, 1].forEach(f => {
        const y = padT + (H - padT - padB) * (1 - f);
        const v = maxAcc * f;
        grid += `<line x1="${padL}" y1="${y}" x2="${W-padR}" y2="${y}" stroke="#e5e7eb" stroke-width="1"/>`;
        grid += `<text x="${padL-8}" y="${y+4}" text-anchor="end" font-size="10" fill="#6b7280">$${(v/1e6).toFixed(0)}M</text>`;
    });
    svg.innerHTML = `${grid}${bars}${labels}
        <text x="${padL-50}" y="${H/2}" transform="rotate(-90,${padL-50},${H/2})" text-anchor="middle" font-size="10" fill="#6b7280">COP ahorrado</text>`;
}

/* ── Fill printable report ──────────────────────────────────── */
function fillReport(d) {
    const r = document.getElementById('report-section');
    if (!r) return;
    r.style.display = 'block';
    const tipoMap = { residencial: 'Residencial', comercial: 'Comercial', industrial: 'Industrial' };
    const regionMap = { caribe: 'Caribe', andina: 'Andina', pacifica: 'Pacífica', orinoquia: 'Orinoquía', amazonia: 'Amazonía' };
    r.innerHTML = `
        <div class="report-preview">
            <h3 style="color:var(--green-dark);margin-bottom:1rem">Informe de Propuesta Fotovoltaica — SoVerdisCo</h3>
            <p style="color:var(--text-muted);font-size:.85rem">Generado: ${new Date().toLocaleDateString('es-CO', {day:'numeric',month:'long',year:'numeric'})} | Ref: SVC-${Date.now().toString(36).toUpperCase()}</p>
            <hr style="margin:1rem 0;border-color:var(--border)">
            <table style="width:100%;font-size:.9rem;border-collapse:collapse">
                <tr><th colspan="2" style="background:var(--green-light);padding:.5rem 1rem;text-align:left;border-radius:4px">Datos de entrada</th></tr>
                <tr><td style="padding:.4rem 0;color:var(--text-muted)">Consumo mensual</td><td><strong>${d.consumo} kWh/mes</strong></td></tr>
                <tr><td style="padding:.4rem 0;color:var(--text-muted)">Tipo de instalación</td><td><strong>${tipoMap[d.tipo]}</strong></td></tr>
                <tr><td style="padding:.4rem 0;color:var(--text-muted)">Región</td><td><strong>${regionMap[d.region]}</strong></td></tr>
                <tr><td style="padding:.4rem 0;color:var(--text-muted)">GHI promedio</td><td><strong>${d.ghi} kWh/m²/día</strong></td></tr>
                <tr><td style="padding:.4rem 0;color:var(--text-muted)">Tarifa referencia</td><td><strong>$${d.tarifa.toLocaleString('es-CO')} COP/kWh</strong></td></tr>
                <tr><th colspan="2" style="background:var(--green-light);padding:.5rem 1rem;text-align:left;border-radius:4px;padding-top:1rem">Dimensionamiento del sistema</th></tr>
                <tr><td style="padding:.4rem 0;color:var(--text-muted)">Potencia pico requerida</td><td><strong>${d.potencia_kWp.toFixed(2)} kWp</strong></td></tr>
                <tr><td style="padding:.4rem 0;color:var(--text-muted)">Número de paneles (400W)</td><td><strong>${d.paneles} paneles</strong></td></tr>
                <tr><td style="padding:.4rem 0;color:var(--text-muted)">Área de instalación aprox.</td><td><strong>${d.area_req} m²</strong></td></tr>
                <tr><th colspan="2" style="background:var(--green-light);padding:.5rem 1rem;text-align:left;border-radius:4px;padding-top:1rem">Análisis financiero</th></tr>
                <tr><td style="padding:.4rem 0;color:var(--text-muted)">Ahorro mensual estimado</td><td><strong>$${d.ahorro_cop.toLocaleString('es-CO')} COP</strong></td></tr>
                <tr><td style="padding:.4rem 0;color:var(--text-muted)">Inversión estimada</td><td><strong>$${d.inversion_cop.toLocaleString('es-CO')} COP</strong></td></tr>
                <tr><td style="padding:.4rem 0;color:var(--text-muted)">Retorno de inversión (con Ley 1715)</td><td><strong>${Math.floor(d.roi_meses/12)} años ${d.roi_meses%12} meses</strong></td></tr>
                <tr><th colspan="2" style="background:var(--green-light);padding:.5rem 1rem;text-align:left;border-radius:4px;padding-top:1rem">Impacto ambiental</th></tr>
                <tr><td style="padding:.4rem 0;color:var(--text-muted)">CO₂ evitado por año</td><td><strong>${d.co2_kg.toLocaleString('es-CO')} kg CO₂eq</strong></td></tr>
                <tr><td style="padding:.4rem 0;color:var(--text-muted)">Equivalencia en árboles</td><td><strong>${d.arboles} árboles nativos/año</strong></td></tr>
            </table>
            <p style="font-size:.75rem;color:var(--text-muted);margin-top:1.5rem">* Estimación basada en datos IDEAM 2024, tarifas CREG, factor de emisión UPME y beneficios fiscales Ley 1715/2014. Los valores son orientativos y requieren estudio de ingeniería detallado.</p>
            <button id="print-report" class="btn btn-outline-green btn-sm" style="margin-top:1rem">🖨 Imprimir / Guardar PDF</button>
        </div>`;
    document.getElementById('print-report')?.addEventListener('click', () => window.print());
}

function escapeHtml(str) {
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}