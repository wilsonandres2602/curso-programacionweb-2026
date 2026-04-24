function cambiarColor() {
    document.body.style.background = "linear-gradient(135deg, #ff6a00, #ee0979)";
}


function mostrarAlerta() {
    alert("¡Hola! Esta es una función de JavaScript.");
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

    let opacidad = val / 100;
    if (opacidad < 0.1) opacidad = 0.1;
    const cards = document.querySelectorAll(".card");

    cards.forEach(card => {
        card.style.background = `rgba(255, 255, 255, ${opacidad})`;
    });
}



function toggleModo(el) {
    document.body.style.background = el.checked ? "#111" : "linear-gradient(135deg, #667eea, #764ba2)";
}



function cargarCiudades() {
    const pais = document.getElementById("pais").value;
    fetch("get_ciudades.php?id_pais=" + pais)
        .then(response => response.text())
        .then(data => {
            document.getElementById("ciudad").innerHTML =
                "<option value=''>Seleccione una ciudad</option>" + data;
        });
}
