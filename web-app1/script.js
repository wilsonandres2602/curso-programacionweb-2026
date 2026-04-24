/* ============================================================
   SCRIPT.JS - App Web Interactiva
   ============================================================ */


/* ============================================================
   Cambiar color de fondo
   ============================================================ */
function cambiarColor() {
    document.body.style.backgroundImage = "none";
    document.body.style.background = "linear-gradient(135deg, #ff6a00, #ee0979)";
}


/* ============================================================
   Mostrar alerta JS
   ============================================================ */
function mostrarAlerta() {
    alert("¡Hola! Esta es una función de JavaScript.");
}


/* ============================================================
   Agregar tarea a la lista
   ============================================================ */
function addTask() {
    const input = document.getElementById("taskInput");
    const valor = input.value.trim();
    if (valor !== "") {
        const li = document.createElement("li");
        li.textContent = valor;
        document.getElementById("taskList").appendChild(li);
        input.value = "";
        input.focus();
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const taskInput = document.getElementById("taskInput");
    if (taskInput) {
        taskInput.addEventListener("keydown", function (e) {
            if (e.key === "Enter") addTask();
        });
    }
});


/* ============================================================
   RÚBRICA #1 — Slider controla la OPACIDAD de la card
   El slider vive en la .controls-bar (FUERA del #mainCard),
   por lo que siempre es visible aunque la card baje a 0.1.
   Rango slider: 0 → 100  |  Opacidad: 0.10 → 1.00
   ============================================================ */
function updateValue(val) {
    const spanVal = document.getElementById("value");
    if (spanVal) spanVal.innerText = val + "%";

    // Brillo a todo EXCEPTO la controls-bar (que tiene el slider)
    const elementos = [
        document.querySelector('.banner'),
        document.querySelector('.container'),
        document.querySelector('footer')
    ];

    elementos.forEach(el => {
        if (el) el.style.filter = "brightness(" + (val / 100) + ")";
    });
}

/* ============================================================
   RÚBRICA #1 / #4 — Toggle Modo Oscuro/Claro
   Default: Modo CLARO (body sin clase).
   Al activar: agrega clase "dark" → activa las CSS variables
   del tema oscuro (paleta completamente diferente).
   El footer y todos los elementos se adaptan automáticamente.
   ============================================================ */
function toggleModo(el) {
    const label = document.getElementById("modeLabel");
    if (el.checked) {
        document.body.classList.add("dark");
        if (label) label.textContent = "🌙 Modo Oscuro";
    } else {
        document.body.classList.remove("dark");
        if (label) label.textContent = "☀️ Modo Claro";
    }
    // Restablecer colores de fondo inline (de cambiarColor)
    document.body.style.background     = "";
    document.body.style.backgroundImage = "";
}


/* ============================================================
   SELECTOR DE PAÍS PERSONALIZADO — RÚBRICA #3
   Funciona leyendo las <option> del <select id="selectPais">
   oculto que PHP llenó con datos de la BD.
   Al elegir un país: actualiza el select oculto y llama
   a cargarCiudades() para el fetch de ciudades.
   ============================================================ */

/* Mapa de emojis de bandera por nombre de país */
const flagMap = {
    "colombia":  "🇨🇴",
    "méxico":    "🇲🇽",
    "mexico":    "🇲🇽",
    "argentina": "🇦🇷",
    "chile":     "🇨🇱",
    "perú":      "🇵🇪",
    "peru":      "🇵🇪",
    "venezuela": "🇻🇪",
    "ecuador":   "🇪🇨",
    "bolivia":   "🇧🇴",
    "uruguay":   "🇺🇾",
    "paraguay":  "🇵🇾",
    "brasil":    "🇧🇷",
    "brazil":    "🇧🇷",
    "españa":    "🇪🇸",
    "spain":     "🇪🇸",
};

function getFlag(name) {
    const key = name.toLowerCase().trim();
    return flagMap[key] || "🌐";
}

// Lista de todas las opciones (cargada una sola vez)
let _paisOpciones = [];

function initCountryPicker() {
    const hiddenSelect = document.getElementById("selectPais");
    if (!hiddenSelect) return;

    // Leemos las opciones del select oculto que PHP llenó
    _paisOpciones = Array.from(hiddenSelect.options)
        .filter(o => o.value !== "")
        .map(o => ({ id: o.value, nombre: o.text }));

    renderPaises("");

    // Cerrar al hacer clic fuera del picker
    document.addEventListener("click", function (e) {
        const picker = document.getElementById("countryPicker");
        if (picker && !picker.contains(e.target)) {
            closePicker();
        }
    });
}

function togglePicker() {
    const panel  = document.getElementById("pickerPanel");
    const arrow  = document.getElementById("pickerArrow");
    const trigger = document.getElementById("pickerTrigger");
    const search = document.getElementById("countrySearch");

    if (panel.classList.contains("open")) {
        closePicker();
    } else {
        panel.classList.add("open");
        arrow.classList.add("open");
        trigger.classList.add("open");
        if (search) {
            search.value = "";
            renderPaises("");
            setTimeout(() => search.focus(), 50);
        }
    }
}

function closePicker() {
    const panel  = document.getElementById("pickerPanel");
    const arrow  = document.getElementById("pickerArrow");
    const trigger = document.getElementById("pickerTrigger");
    if (panel)  panel.classList.remove("open");
    if (arrow)  arrow.classList.remove("open");
    if (trigger) trigger.classList.remove("open");
}

function filtrarPaises(query) {
    renderPaises(query);
}

function renderPaises(query) {
    const list = document.getElementById("pickerList");
    if (!list) return;

    const filtrados = _paisOpciones.filter(p =>
        p.nombre.toLowerCase().includes(query.toLowerCase())
    );

    if (filtrados.length === 0) {
        list.innerHTML = '<div class="picker-empty">No se encontraron países</div>';
        return;
    }

    list.innerHTML = "";
    const hiddenSelect = document.getElementById("selectPais");
    const valorActual  = hiddenSelect ? hiddenSelect.value : "";

    filtrados.forEach(p => {
        const item = document.createElement("div");
        item.className = "country-item" + (p.id === valorActual ? " selected" : "");
        item.innerHTML = `<span class="flag">${getFlag(p.nombre)}</span> ${p.nombre}`;
        item.addEventListener("click", () => seleccionarPais(p.id, p.nombre));
        list.appendChild(item);
    });
}

function seleccionarPais(id, nombre) {
    // Actualizamos el select oculto
    const hiddenSelect = document.getElementById("selectPais");
    if (hiddenSelect) hiddenSelect.value = id;

    // Actualizamos el texto visible del trigger
    const pickerText = document.getElementById("pickerText");
    if (pickerText) {
        pickerText.textContent = getFlag(nombre) + "  " + nombre;
    }

    closePicker();

    // Cargamos las ciudades para el país seleccionado
    cargarCiudades();
}


/* ============================================================
   RÚBRICA #3 — Cargar ciudades por país (fetch → get_ciudades.php)
   ============================================================ */
function cargarCiudades() {
    const idPais       = document.getElementById("selectPais").value;
    const selectCiudad = document.getElementById("selectCiudad");

    selectCiudad.innerHTML = '<option value="">Cargando...</option>';
    selectCiudad.disabled  = true;

    if (!idPais) {
        selectCiudad.innerHTML = '<option value="">🏙️ Selecciona un país primero</option>';
        return;
    }

    fetch("get_ciudades.php?id_pais=" + idPais)
        .then(res => res.json())
        .then(data => {
            selectCiudad.disabled  = false;
            selectCiudad.innerHTML = '<option value="">🏙️ Selecciona una ciudad</option>';
            data.forEach(ciudad => {
                const opt       = document.createElement("option");
                opt.value       = ciudad.id;
                opt.textContent = ciudad.nombre;
                selectCiudad.appendChild(opt);
            });
        })
        .catch(() => {
            selectCiudad.innerHTML = '<option value="">Error al cargar ciudades</option>';
        });
}


/* ============================================================
   RÚBRICA #3 — Guardar Destino
   ============================================================ */
function guardarDestino() {
    const nombre   = document.getElementById("inputNombre").value.trim();
    const idPais   = document.getElementById("selectPais").value;
    const idCiudad = document.getElementById("selectCiudad").value;

    if (!nombre || !idPais || !idCiudad) {
        mostrarError("⚠️ Por favor completa todos los campos.");
        return;
    }

    const params = new URLSearchParams({ nombre, id_pais: idPais, id_ciudad: idCiudad });

    fetch("guardar_viaje.php", {
        method:  "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body:    params.toString()
    })
    .then(res => res.json())
    .then(data => {
        if (data.ok) {
            abrirModal(data.usuario, data.pais, data.ciudad);
            // Limpiar formulario
            document.getElementById("inputNombre").value = "";
            document.getElementById("selectPais").value  = "";
            document.getElementById("pickerText").textContent = "Selecciona un país";
            document.getElementById("selectCiudad").innerHTML =
                '<option value="">🏙️ Selecciona un país primero</option>';
            document.getElementById("selectCiudad").disabled = true;
        } else {
            mostrarError("❌ Error: " + (data.error || "Error desconocido"));
        }
    })
    .catch(() => mostrarError("❌ Error de conexión con el servidor."));
}

function mostrarError(msg) {
    const el = document.getElementById("mensaje-guardado");
    if (el) { el.style.color = "#e05252"; el.textContent = msg; }
}


/* ============================================================
   MODAL de confirmación
   ============================================================ */
function abrirModal(usuario, pais, ciudad) {
    document.getElementById("modal-usuario").textContent = usuario;
    document.getElementById("modal-pais").textContent    = pais;
    document.getElementById("modal-ciudad").textContent  = ciudad;
    document.getElementById("modalOverlay").classList.add("active");
    const msg = document.getElementById("mensaje-guardado");
    if (msg) msg.textContent = "";
}

function cerrarModal() {
    document.getElementById("modalOverlay").classList.remove("active");
}


/* ============================================================
   INICIALIZACIÓN
   ============================================================ */
document.addEventListener("DOMContentLoaded", function () {
    // Inicializar el picker de países
    initCountryPicker();

    // Cerrar modal al clic fuera del cuadro
    const overlay = document.getElementById("modalOverlay");
    if (overlay) {
        overlay.addEventListener("click", function (e) {
            if (e.target === overlay) cerrarModal();
        });
    }
});
