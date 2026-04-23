function cambiarColor() {
    document.body.style.background = "linear-gradient(135deg, #ff6a00, #ee0979)";
}

function mostrarAlerta() {
    document.getElementById("modalAlerta").classList.add("activo");
}

function cerrarAlerta() {
    document.getElementById("modalAlerta").classList.remove("activo");
}

function cerrarAlertaFuera(event) {
    if (event.target.id === "modalAlerta") {
        cerrarAlerta();
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

function updateValue(val) {
    document.getElementById("value").innerText = val;
    const opacidad = 0.1 + (val / 100) * 0.9;
    document.querySelector(".card").style.opacity = opacidad;
}

function toggleModo(el) {
    if (el.checked) {
        document.body.style.background = "#111";
        document.body.classList.add("dark-mode");
    } else {
        document.body.style.background = "linear-gradient(135deg, #667eea, #764ba2)";
        document.body.classList.remove("dark-mode");
    }
}

function filtrarCiudades() {
    const paisSeleccionado = document.getElementById("selectPais").value;
    const selectCiudad = document.getElementById("selectCiudad");
    const opciones = selectCiudad.querySelectorAll("option");

    opciones.forEach(function(opcion) {
        if (opcion.value === "") {
            opcion.style.display = "block";
        } else if (opcion.getAttribute("data-pais") === paisSeleccionado) {
            opcion.style.display = "block";
        } else {
            opcion.style.display = "none";
        }
    });

    selectCiudad.value = "";
}