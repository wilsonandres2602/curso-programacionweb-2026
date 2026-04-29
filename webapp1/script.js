// ============================================
//  SCRIPT.JS — App Web Interactiva
// ============================================

// ── Cambiar color de fondo ──
function cambiarColor() {
  const colores = [
    "linear-gradient(135deg, #ff6a00, #ee0979)",
    "linear-gradient(135deg, #11998e, #38ef7d)",
    "linear-gradient(135deg, #fc466b, #3f5efb)",
    "linear-gradient(135deg, #f7971e, #ffd200)",
    "linear-gradient(135deg, #1a1a2e, #0f3460)",
  ];
  const actual = document.body.style.background;
  const siguiente = colores[Math.floor(Math.random() * colores.length)];
  document.body.style.background = siguiente;
}

// ── Modal alerta ──
function mostrarAlerta() {
  const modal = document.getElementById("modal-alerta");
  modal.classList.remove("hidden");
}

function cerrarAlerta() {
  const modal = document.getElementById("modal-alerta");
  modal.classList.add("hidden");
}

// ── Lista de tareas ──
function addTask() {
  const input = document.getElementById("taskInput");
  const val = input.value.trim();
  if (!val) return;
  const li = document.createElement("li");
  li.textContent = val;
  document.getElementById("taskList").appendChild(li);
  input.value = "";
}

// ── Slider: cambia opacidad de la card ──
function updateValue(val) {
  document.getElementById("value").innerText = val;

  // Opacidad proporcional de 0.1 a 1.0
  const opacity = 0.1 + (val / 100) * 0.9;
  const card = document.getElementById("dashboard-card");
  if (card) {
    card.style.background = `rgba(255, 255, 255, ${opacity * 0.15})`;
  }
}

// ── Modo oscuro ──
function toggleModo(el) {
  if (el.checked) {
    document.body.classList.add("dark-mode");
    document.body.style.background = "#0a0a0a";
  } else {
    document.body.classList.remove("dark-mode");
    document.body.style.background =
      "linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%)";
  }
}

// ── Cargar ciudades desde el servidor ──
function cargarCiudades(idPais) {
  const select = document.getElementById("selectCiudad");
  select.innerHTML = '<option value="">-- Selecciona una ciudad --</option>';
  if (!idPais) return;

  fetch(`get_ciudades.php?id_pais=${idPais}`)
    .then((r) => r.json())
    .then((data) => {
      data.forEach((c) => {
        const opt = document.createElement("option");
        opt.value = c.id;
        opt.textContent = c.nombre;
        select.appendChild(opt);
      });
    })
    .catch(() => {
      select.innerHTML = '<option value="">Error cargando ciudades</option>';
    });
}

// ── Guardar destino en la BD ──
function guardarDestino() {
  const nombre   = document.getElementById("nombreUsuario").value.trim();
  const idPais   = document.getElementById("selectPais").value;
  const idCiudad = document.getElementById("selectCiudad").value;
  const msg      = document.getElementById("msg-registro");

  if (!nombre || !idPais || !idCiudad) {
    msg.style.color = "#ff4d4d";
    msg.textContent = "⚠️ Por favor completa todos los campos.";
    return;
  }

  const body = new FormData();
  body.append("nombre_usuario", nombre);
  body.append("id_pais", idPais);
  body.append("id_ciudad", idCiudad);

  msg.style.color = "rgba(255,255,255,0.5)";
  msg.textContent = "Guardando...";

  fetch("guardar_destino.php", { method: "POST", body })
    .then((r) => r.json())
    .then((data) => {
      if (data.ok) {
        msg.style.color = "#00ffaa";
        msg.textContent = "✅ Destino guardado correctamente.";
        document.getElementById("nombreUsuario").value = "";
        document.getElementById("selectPais").value = "";
        document.getElementById("selectCiudad").innerHTML =
          '<option value="">-- Selecciona una ciudad --</option>';
      } else {
        msg.style.color = "#ff4d4d";
        msg.textContent = "❌ Error: " + (data.error || "desconocido");
      }
    })
    .catch(() => {
      msg.style.color = "#ff4d4d";
      msg.textContent = "❌ Error de conexión con el servidor.";
    });
}
