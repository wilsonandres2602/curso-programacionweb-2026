function cambiarColor() {
    document.body.style.background = "linear-gradient(135deg, #000000, #ffffff)";
}
 
function mostrarAlerta() {
    alert("Mensaje de alerta.");
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

    // Calcular opacidad entre 0.1 y 1.0
    const opacity = 0.1 + (val / 100) * 0.9;

    // Cambiar opacidad de la card principal
    const card = document.querySelector(".card");
    if (card) {
        card.style.opacity = opacity.toFixed(2);
    }
}

// Inicializar opacidad al cargar la página
window.addEventListener("load", () => {
    updateValue(50);
});
 
function toggleModo(el) {
    // Cambiar fondo del body
    document.body.style.background = el.checked ? "#111" : "linear-gradient(135deg, #667eea, #764ba2)";
    
    // Cambiar estilo del footer
    const footer = document.querySelector(".footer");
    if (footer) {
        if (el.checked) {
            footer.classList.add("dark-mode");
        } else {
            footer.classList.remove("dark-mode");
        }
    }
}

// Función para cargar ciudades según el país seleccionado
function cargarCiudades() {
    const paisId = document.getElementById("pais").value;
    const ciudadSelect = document.getElementById("ciudad");
    
    if (paisId === "") {
        ciudadSelect.innerHTML = '<option value="">Selecciona una ciudad</option>';
        return;
    }
    
    // Hacer petición AJAX para obtener ciudades
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "get_ciudades.php?id_pais=" + paisId, true);
    xhr.onload = function() {
        if (this.status === 200) {
            ciudadSelect.innerHTML = this.responseText;
        }
    };
    xhr.send();
}