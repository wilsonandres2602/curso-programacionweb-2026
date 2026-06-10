@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap');

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --bg:          #07090F;
    --surface:     rgba(255,255,255,0.04);
    --surface-hi:  rgba(255,255,255,0.07);
    --border:      rgba(255,255,255,0.08);
    --border-hi:   rgba(99,102,241,0.5);
    --indigo:      #6366F1;
    --indigo-dark: #4F46E5;
    --cyan:        #22D3EE;
    --text:        #EEF2FF;
    --text-sub:    #94A3B8;
    --error:       #F87171;
    --success:     #34D399;
    --r:           18px;
    --r-sm:        10px;
}

html { scroll-behavior: smooth; }

body {
    font-family: 'Inter', sans-serif;
    background-color: var(--bg);
    color: var(--text);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    text-align: center;
    overflow-x: hidden;
    transition: background-color 0.5s;
    position: relative;
}

/* Aurora orbs */
body::before, body::after {
    content: '';
    position: fixed;
    border-radius: 50%;
    filter: blur(130px);
    pointer-events: none;
    z-index: 0;
    opacity: 0.3;
    transition: opacity 0.5s;
}
body::before {
    width: 700px; height: 700px;
    background: radial-gradient(circle, #6366F1, transparent 70%);
    top: -200px; left: -200px;
}
body::after {
    width: 600px; height: 600px;
    background: radial-gradient(circle, #22D3EE, transparent 70%);
    bottom: -150px; right: -150px;
}

.banner, .container, footer { position: relative; z-index: 1; }

/* Banner */
.banner {
    width: 100%;
    max-height: 220px;
    overflow: hidden;
    border-bottom: 1px solid var(--border);
}
.banner iframe, .banner img {
    width: 100%; height: 220px;
    object-fit: cover;
    border: none; display: block;
    opacity: 0.7;
}

/* Container */
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    flex: 1;
    padding: 30px 20px;
}

/* Card */
.card {
    background: var(--surface);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid var(--border);
    border-radius: var(--r);
    padding: 48px 44px;
    width: 460px;
    max-width: 95vw;
    box-shadow: 0 0 0 1px rgba(255,255,255,0.02),
                0 24px 60px rgba(0,0,0,0.6),
                inset 0 1px 0 rgba(255,255,255,0.06);
    text-align: left;
}

/* Typography */
h1, h2 {
    font-family: 'Space Grotesk', sans-serif;
    font-weight: 700;
    letter-spacing: -0.5px;
    text-align: left;
}
h1 {
    font-size: 2rem;
    background: linear-gradient(135deg, var(--text) 40%, var(--text-sub));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 6px;
}
h2 {
    font-size: 1.35rem;
    color: var(--text);
    margin-bottom: 6px;
}
p {
    color: var(--text-sub);
    font-size: 0.9rem;
    line-height: 1.6;
    margin-bottom: 20px;
}

/* Labels */
label:not(.switch) {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-sub);
    text-transform: uppercase;
    letter-spacing: 0.09em;
    margin: 18px 0 6px;
}

/* Inputs + selects */
input:not([type="range"]):not([type="checkbox"]),
select {
    display: block;
    width: 100%;
    margin: 6px 0;
    padding: 13px 16px;
    border-radius: var(--r-sm);
    border: 1px solid var(--border);
    background: var(--surface-hi);
    color: var(--text);
    font-family: 'Inter', sans-serif;
    font-size: 0.93rem;
    box-sizing: border-box;
    transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
    outline: none;
}
input:not([type="range"]):not([type="checkbox"])::placeholder { color: var(--text-sub); }
input:not([type="range"]):not([type="checkbox"]):focus,
select:focus {
    border-color: var(--border-hi);
    background: rgba(99,102,241,0.08);
    box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
}

select {
    appearance: none; cursor: pointer;
    background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'/></svg>");
    background-repeat: no-repeat;
    background-position: right 14px center;
    background-size: 16px;
}
select option { background: #111827; color: var(--text); }
select:disabled { opacity: 0.5; cursor: not-allowed; }

/* Buttons */
button {
    display: block;
    width: 100%;
    margin: 14px 0 0;
    padding: 14px 20px;
    border-radius: 30px;
    border: none;
    font-family: 'Space Grotesk', sans-serif;
    font-size: 0.93rem;
    font-weight: 600;
    letter-spacing: 0.03em;
    color: white;
    background: linear-gradient(135deg, var(--indigo) 0%, var(--indigo-dark) 100%);
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(99,102,241,0.3);
    transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.3s ease;
}
button:hover {
    background: linear-gradient(135deg, #818CF8 0%, var(--indigo) 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(99,102,241,0.45);
}
button:active { transform: translateY(1px); box-shadow: 0 2px 10px rgba(99,102,241,0.25); }
button:disabled { opacity: 0.4; cursor: not-allowed; transform: none; box-shadow: none; }

/* Section blocks */
.section {
    margin-top: 20px;
    padding: 18px;
    background: rgba(255,255,255,0.025);
    border-radius: var(--r-sm);
    border: 1px solid var(--border);
}
.section-label {
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--text-sub);
    text-transform: uppercase;
    letter-spacing: 0.12em;
    margin-bottom: 12px;
    display: block;
}

/* Task list */
#taskList {
    list-style: none;
    margin-top: 10px;
}
#taskList li {
    padding: 9px 14px;
    background: rgba(99,102,241,0.08);
    border: 1px solid rgba(99,102,241,0.18);
    border-radius: 8px;
    margin-top: 7px;
    font-size: 0.88rem;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 8px;
    animation: slideIn 0.2s ease;
}
#taskList li::before {
    content: '›';
    color: var(--indigo);
    font-size: 1.2rem;
    font-weight: bold;
    flex-shrink: 0;
}
@keyframes slideIn {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Range */
.range-row {
    display: flex;
    align-items: center;
    gap: 12px;
}
input[type="range"] {
    flex: 1;
    margin: 8px 0;
    background: transparent;
    cursor: pointer;
}
input[type="range"]::-webkit-slider-runnable-track {
    height: 4px;
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
}
input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    height: 18px; width: 18px;
    border-radius: 50%;
    background: var(--indigo);
    margin-top: -7px;
    box-shadow: 0 0 10px rgba(99,102,241,0.6);
    cursor: pointer;
    transition: background 0.2s;
}
input[type="range"]:hover::-webkit-slider-thumb { background: #818CF8; }
#value {
    font-family: 'Space Grotesk', sans-serif;
    font-weight: 700;
    color: var(--cyan);
    font-size: 1.05rem;
    min-width: 32px;
    text-align: right;
}

/* Switch */
.switch-row {
    display: flex;
    align-items: center;
    gap: 14px;
}
.switch-row p { margin: 0; font-size: 0.88rem; color: var(--text-sub); }
.switch {
    position: relative;
    display: inline-block;
    width: 44px; height: 24px;
    flex-shrink: 0;
}
.switch input { display: none; }
.slider {
    position: absolute;
    inset: 0;
    background: rgba(255,255,255,0.1);
    border: 1px solid var(--border);
    border-radius: 24px;
    cursor: pointer;
    transition: background 0.3s, border-color 0.3s;
}
.slider::before {
    position: absolute;
    content: "";
    height: 16px; width: 16px;
    left: 3px; bottom: 3px;
    background: white;
    border-radius: 50%;
    transition: transform 0.3s;
    box-shadow: 0 2px 5px rgba(0,0,0,0.3);
}
input:checked + .slider { background: var(--indigo); border-color: var(--indigo-dark); }
input:checked + .slider::before { transform: translateX(20px); }

/* Logout */
.logout-link {
    display: inline-block;
    margin-top: 22px;
    color: var(--text-sub);
    font-size: 0.82rem;
    font-weight: 500;
    text-decoration: none;
    letter-spacing: 0.04em;
    transition: color 0.2s;
    text-align: center;
    width: 100%;
}
.logout-link:hover { color: var(--error); }

/* Section divider */
.card-divider {
    border: none;
    border-top: 1px solid var(--border);
    margin: 28px 0;
}

/* Error message on login */
.login-error {
    background: rgba(248,113,113,0.1);
    border: 1px solid rgba(248,113,113,0.25);
    color: var(--error);
    font-size: 0.85rem;
    padding: 10px 14px;
    border-radius: var(--r-sm);
    margin-top: 12px;
    text-align: center;
}

/* Registro mensaje */
#mensajeRegistro {
    margin-top: 12px;
    font-size: 0.88rem;
    font-weight: 500;
    min-height: 20px;
    transition: all 0.3s;
    text-align: center;
}
#mensajeRegistro.success {
    background: rgba(52,211,153,0.1);
    border: 1px solid rgba(52,211,153,0.25);
    color: var(--success);
    padding: 10px 14px;
    border-radius: var(--r-sm);
}
#mensajeRegistro.error {
    background: rgba(248,113,113,0.1);
    border: 1px solid rgba(248,113,113,0.25);
    color: var(--error);
    padding: 10px 14px;
    border-radius: var(--r-sm);
}

/* Spacing between containers */
#dashboard { margin-bottom: 0; padding-bottom: 0; }
#registro-ubicacion { padding-top: 0; }

/* Footer */
footer {
    position: relative; z-index: 1;
    background: rgba(255,255,255,0.02);
    border-top: 1px solid var(--border);
    color: var(--text-sub);
    text-align: center;
    padding: 22px 0;
    margin-top: auto;
    font-size: 0.8rem;
    letter-spacing: 0.04em;
    backdrop-filter: blur(10px);
    transition: background 0.5s;
}
.footer-content { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
footer a { color: var(--cyan); text-decoration: none; font-weight: 600; transition: opacity 0.2s; }
footer a:hover { opacity: 0.7; }

/* Scrollbar */
::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: rgba(99,102,241,0.35); border-radius: 10px; }

/* Responsive */
@media (max-width: 600px) {
    .card { padding: 28px 22px; }
    h1 { font-size: 1.55rem; }
    h2 { font-size: 1.15rem; }
    .banner, .banner iframe, .banner img { max-height: 150px; height: 150px; }
}
