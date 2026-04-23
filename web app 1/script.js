function cambiarColor() {
    document.body.style.background = "linear-gradient(135deg, #455151, #b1bd0b)";
}

function mostrarAlerta() {
    document.getElementById("modal-overlay").classList.remove("hidden");
}

function cerrarModal() {
    document.getElementById("modal-overlay").classList.add("hidden");
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

function updateOpacidad(val) {
    const opacidad = val / 100;
    document.querySelector('.card').style.opacity = opacidad;
    document.getElementById("value").innerText = Math.round(opacidad * 10) / 10;
}

function toggleModo(el) {
    if (el.checked) {
        document.body.style.background = "#111";
        document.body.classList.add("dark");
    } else {
        document.body.style.background = "linear-gradient(135deg, #667eea, #764ba2)";
        document.body.classList.remove("dark");
    }
}

function cargarCiudades(id_pais) {
    const select = document.getElementById('selectCiudad');
    select.innerHTML = '<option value="">-- Selecciona una ciudad --</option>';

    if (!id_pais) return;

    const filtradas = ciudadesPorPais.filter(c => c.id_pais == id_pais);
    filtradas.forEach(c => {
        const option = document.createElement('option');
        option.value = c.id;
        option.textContent = c.nombre;
        select.appendChild(option);
    });
}