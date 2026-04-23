function cambiarColor() {
    document.body.style.background = "linear-gradient(135deg, #ff6a00, #ee0979)";
}

// Reemplaza el alert() por el modal bonito
function mostrarAlerta() {
    document.getElementById("modalOverlay").classList.add("active");
}

function cerrarModal(event) {
    // Si se hizo click en el overlay (fondo) o en el botón cerrar, cierra
    if (!event || event.target === document.getElementById("modalOverlay") || event.target.classList.contains("modal-close")) {
        document.getElementById("modalOverlay").classList.remove("active");
    }
}

function addTask() {
    const input = document.getElementById("taskInput");
    if(input.value.trim() !== "") {
        const li = document.createElement("li");
        li.textContent = input.value;
        li.style.textAlign = "left";
        document.getElementById("taskList").appendChild(li);
        input.value = "";
    }
}

// PUNTO 1: Slider cambia opacidad de la card (0.1 a 1.0)
function updateValue(val) {
    document.getElementById("value").innerText = val;
    const opacidad = 0.1 + (val / 100) * 0.9;
    document.getElementById("mainCard").style.opacity = opacidad;
}

function toggleModo(el) {
    document.body.style.background = el.checked ? "#111" : "linear-gradient(135deg, #667eea, #764ba2)";
}

function cargarCiudades(idPais) {
    if (idPais === "") {
        document.getElementById("ciudad").innerHTML = "<option value=''>Seleccione ciudad</option>";
        return;
    }
    fetch("get_ciudades.php?id_pais=" + idPais)
        .then(response => response.text())
        .then(data => {
            document.getElementById("ciudad").innerHTML = data;
        })
        .catch(error => console.error("Error cargando ciudades:", error));
}