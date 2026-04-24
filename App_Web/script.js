function cambiarColor() {
  document.body.style.background = "linear-gradient(135deg, #ff6a00, #ee0979)";
}

// ALERTA
function mostrarAlerta() {
  document.getElementById("customAlert").classList.remove("hidden");
}

function cerrarAlerta(e) {
  if (e.target.id === "customAlert") {
    document.getElementById("customAlert").classList.add("hidden");
  }
}

function cerrarAlertaBoton() {
  document.getElementById("customAlert").classList.add("hidden");
}

// TAREAS
function addTask() {
  const input = document.getElementById("taskInput");
  const li = document.createElement("li");
  li.textContent = input.value;
  document.getElementById("taskList").appendChild(li);
  input.value = "";
}

// SLIDER -- OPACIDAD
function updateValue(val) {
  document.getElementById("value").innerText = val;

  let opacidad = val / 100;
  if (opacidad < 0.1) {
    opacidad = 0.1;
  }

  document.querySelector(".card").style.opacity = opacidad;
}

// MODO OSCURO
function toggleModo(el) {
  document.body.style.background = el.checked ? "#111" : "linear-gradient(135deg, #667eea, #764ba2)";
}

// CARGAR CIUDADES
function cargarCiudades(idPais) {
  fetch("get_ciudades.php?id_pais=" + idPais)
  .then(res => res.json())
  .then(data => {
    let select = document.getElementById("ciudad");
    select.innerHTML = "";

    for (let i = 0; i < data.length; i++) {
      let option = document.createElement("option");
      option.value = data[i].id;
      option.textContent = data[i].nombre;
      select.appendChild(option);
    }
  });
}

// GUARDAR
function guardarDestino(e) {
  e.preventDefault();

  let form = new FormData(document.getElementById("formUbicacion"));

  fetch("guardar_destino.php", {
    method: "POST",
    body: form
  })
  .then(res => res.json())
  .then(data => {
    document.getElementById("mensajeRegistro").innerText = data.message;
  });
}