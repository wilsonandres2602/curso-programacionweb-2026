// ── Audio (Web Audio API) ─────────────────────────────────────────────────────
let audioCtx = null;
let oscillator = null;
let gainNode = null;
let isPlaying = false;

function initAudio() {
  if (audioCtx) return;
  audioCtx = new (window.AudioContext || window.webkitAudioContext)();

  oscillator = audioCtx.createOscillator();
  gainNode   = audioCtx.createGain();

  oscillator.type            = "sine";
  oscillator.frequency.value = 440;
  gainNode.gain.value        = 0.5;

  oscillator.connect(gainNode);
  gainNode.connect(audioCtx.destination);
  oscillator.start();
}

function toggleAudio() {
  initAudio();

  if (isPlaying) {
    gainNode.gain.setTargetAtTime(0, audioCtx.currentTime, 0.05);
    setTimeout(() => audioCtx.suspend(), 200);
    document.getElementById("btnPlay").textContent = "▶";
  } else {
    audioCtx.resume();
    const vol = parseInt(document.getElementById("volValue").textContent, 10);
    gainNode.gain.setTargetAtTime(vol / 100, audioCtx.currentTime, 0.05);
    document.getElementById("btnPlay").textContent = "⏹";
  }

  isPlaying = !isPlaying;
}

// ── Modal alerta ──────────────────────────────────────────────────────────────
function mostrarAlerta() {
  document.getElementById("modalOverlay").classList.remove("hidden");
}

function cerrarAlerta(event) {
  if (event && event.target !== document.getElementById("modalOverlay")) return;
  document.getElementById("modalOverlay").classList.add("hidden");
}

// ── Login (contra BD vía PHP) ─────────────────────────────────────────────────
async function login() {
  const username = document.getElementById("username").value.trim();
  const password = document.getElementById("password").value;
  const errorEl  = document.getElementById("error");
  const btnLogin = document.querySelector("#loginView button");

  if (!username || !password) {
    errorEl.innerText = "Completa usuario y contraseña";
    return;
  }

  // Feedback visual mientras espera
  btnLogin.disabled     = true;
  btnLogin.textContent  = "Verificando...";
  errorEl.innerText     = "";

  try {
    const res  = await fetch("login.php", {
      method : "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body   : `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
    });
    const data = await res.json();

    if (data.ok) {
      // Mostrar nombre real del usuario en el dashboard
      document.querySelector(".dash-header h1").textContent =
        `Bienvenido, ${data.nombre_completo} 👋`;

      document.getElementById("loginView").style.display = "none";
      document.getElementById("dashboard").classList.remove("hidden");
      cargarPaises();
      cargarRegistros();
    } else {
      errorEl.innerText = data.error || "Credenciales incorrectas";
    }
  } catch (e) {
    errorEl.innerText = "No se pudo conectar con el servidor";
  } finally {
    btnLogin.disabled    = false;
    btnLogin.textContent = "Ingresar";
  }
}

// ── Dashboard ─────────────────────────────────────────────────────────────────
function cambiarColor() {
  document.getElementById("app").style.background =
    "linear-gradient(135deg, #ff6a00, #ee0979)";
}

function addTask() {
  const input = document.getElementById("taskInput");
  if (!input.value.trim()) return;
  const li = document.createElement("li");
  li.textContent = input.value.trim();
  document.getElementById("taskList").appendChild(li);
  input.value = "";
}

// ── Volumen ────────────────────────────────────────────────────────────────────
// Al mover el slider, además de mostrar el número, cambia la opacidad de mainCard
function updateVolumen(val) {
  document.getElementById("volValue").innerText = val;

  // Opacidad proporcional: slider 0→100 mapea a opacity 0.1→1.0
  const opacity = 0.1 + (val / 100) * 0.9;
  document.getElementById("mainCard").style.opacity = opacity;

  if (gainNode && isPlaying) {
    gainNode.gain.setTargetAtTime(val / 100, audioCtx.currentTime, 0.02);
  }
}

// ── Brillo ────────────────────────────────────────────────────────────────────
function updateBrillo(val) {
  document.getElementById("brilloValue").innerText = val;
  document.body.style.filter = "brightness(" + (val / 100) + ")";
}

// ── Modo oscuro ───────────────────────────────────────────────────────────────
function toggleModo(el) {
  document.getElementById("app").style.background = el.checked
    ? "#111"
    : "linear-gradient(135deg, #667eea, #764ba2)";
  // Adaptar footer al modo oscuro
  document.body.classList.toggle("dark-mode", el.checked);
}

// ── Registro de Ubicación ─────────────────────────────────────────────────────

// Cargar países desde PHP
async function cargarPaises() {
  try {
    const res  = await fetch("get_paises.php");
    const data = await res.json();
    const sel  = document.getElementById("selectPais");
    sel.innerHTML = '<option value="">-- Selecciona un país --</option>';
    data.forEach(p => {
      const opt = document.createElement("option");
      opt.value       = p.id;
      opt.textContent = p.nombre;
      sel.appendChild(opt);
    });
  } catch (e) {
    console.error("Error cargando países:", e);
  }
}

// Cargar ciudades filtradas por país
async function cargarCiudades(idPais) {
  const sel = document.getElementById("selectCiudad");
  sel.innerHTML = '<option value="">-- Selecciona una ciudad --</option>';
  if (!idPais) return;

  try {
    const res  = await fetch(`get_ciudades.php?id_pais=${idPais}`);
    const data = await res.json();
    data.forEach(c => {
      const opt = document.createElement("option");
      opt.value       = c.id;
      opt.textContent = c.nombre;
      sel.appendChild(opt);
    });
  } catch (e) {
    console.error("Error cargando ciudades:", e);
  }
}

// Guardar destino
async function guardarDestino() {
  const nombre  = document.getElementById("regNombre").value.trim();
  const idPais  = document.getElementById("selectPais").value;
  const idCiud  = document.getElementById("selectCiudad").value;
  const msg     = document.getElementById("feedbackMsg");

  if (!nombre || !idPais || !idCiud) {
    mostrarFeedback("⚠️ Completa todos los campos.", false);
    return;
  }

  try {
    const res  = await fetch("guardar_destino.php", {
      method : "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body   : `nombre=${encodeURIComponent(nombre)}&id_pais=${idPais}&id_ciudad=${idCiud}`
    });
    const data = await res.json();

    if (data.ok) {
      mostrarFeedback("✅ ¡Destino guardado correctamente!", true);
      document.getElementById("regNombre").value = "";
      document.getElementById("selectPais").value = "";
      document.getElementById("selectCiudad").innerHTML =
        '<option value="">-- Selecciona una ciudad --</option>';
      cargarRegistros();
    } else {
      mostrarFeedback("❌ Error: " + (data.error || "intenta de nuevo."), false);
    }
  } catch (e) {
    mostrarFeedback("❌ No se pudo conectar con el servidor.", false);
  }
}

// Cargar últimos registros
async function cargarRegistros() {
  try {
    const res  = await fetch("get_registros.php");
    const data = await res.json();
    const ul   = document.getElementById("registrosList");
    ul.innerHTML = "";
    data.forEach(r => {
      const li = document.createElement("li");
      li.textContent = `${r.nombre_usuario} → ${r.ciudad}, ${r.pais}`;
      ul.appendChild(li);
    });
  } catch (e) {
    console.error("Error cargando registros:", e);
  }
}

function mostrarFeedback(texto, ok) {
  const el = document.getElementById("feedbackMsg");
  el.textContent = texto;
  el.className   = "feedback " + (ok ? "ok" : "err");
  el.classList.remove("hidden");
  setTimeout(() => el.classList.add("hidden"), 3500);
}