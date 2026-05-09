function cambiarColor() {
    document.body.style.background = "linear-gradient(135deg, #ff6a00, #ee0979)";
}

function mostrarAlerta() {
    alert("¡Hola! Esta es una función de JavaScript.");
}

function addTask() {
    const input = document.getElementById("taskInput");
    if (input.value.trim() !== "") {
        const li = document.createElement("li");
        li.textContent = input.value;
        li.style.textAlign = "left";
        document.getElementById("taskList").appendChild(li);
        input.value = "";
    }
}

function updateValue(val) {
    document.getElementById("value").innerText = val;
    // Seleccionar la tarjeta principal
    const card = document.querySelector('.card');
    if (card) {
        // La opacidad en CSS es de 0.0 a 1.0. Dividimos el valor del slider entre 100.
        // Como el slider tendrá min="10", el valor irá de 0.1 a 1.0.
        card.style.opacity = val / 100;
    }
}

function toggleModo(el) {
    document.body.style.background = el.checked ? "#111" : "linear-gradient(135deg, #667eea, #764ba2)";
}