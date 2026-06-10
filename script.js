// Funciones existentes
function cambiarColor() {
    document.body.style.background = "linear-gradient(135deg, #ff6a00, #ee0979)";
}

function mostrarAlerta() {
    alert("¡Hola! Esta es una función de JavaScript.");
}

function addTask() {
    const input = document.getElementById("taskInput");
    const task = input.value.trim();
    if (task !== "") {
        const li = document.createElement("li");
        li.textContent = task;
        li.style.textAlign = "left";
        document.getElementById("taskList").appendChild(li);
        input.value = "";
    }
}

function updateValue(val) {
    document.getElementById("value").innerText = val;
    const opacidad = 0.1 + (val / 100) * 0.9;
    const card = document.querySelector('.card');
    if (card) {
        card.style.backgroundColor = `rgba(255, 255, 255, ${opacidad * 0.15})`;
        card.style.backdropFilter = `blur(${15 * opacidad}px)`;
    }
}

function toggleModo(el) {
    document.body.style.background = el.checked ? "#111" : "linear-gradient(135deg, #667eea, #764ba2)";
    // Ajustar footer en modo oscuro
    const footer = document.querySelector('.footer');
    if (footer) {
        footer.style.backgroundColor = el.checked ? '#1a1a1a' : 'rgba(0,0,0,0.7)';
    }
}

// Nuevas funciones para Registro de Ubicación
function cargarCiudades(idPais) {
    const ciudadSelect = document.getElementById('ciudad');
    ciudadSelect.disabled = true;
    ciudadSelect.innerHTML = '<option value="">Cargando ciudades...</option>';

    if (!idPais) {
        ciudadSelect.innerHTML = '<option value="">Primero seleccione un país</option>';
        return;
    }

    fetch(`get_ciudades.php?id_pais=${idPais}`)
        .then(response => response.json())
        .then(data => {
            ciudadSelect.innerHTML = '<option value="">Seleccione una ciudad</option>';
            if (data.length === 0) {
                ciudadSelect.innerHTML = '<option value="">No hay ciudades disponibles</option>';
            } else {
                data.forEach(ciudad => {
                    const option = document.createElement('option');
                    option.value = ciudad.id;
                    option.textContent = ciudad.nombre;
                    ciudadSelect.appendChild(option);
                });
            }
            ciudadSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error al cargar ciudades:', error);
            ciudadSelect.innerHTML = '<option value="">Error al cargar ciudades</option>';
            ciudadSelect.disabled = false;
        });
}

function guardarDestino(event) {
    event.preventDefault();
    const form = document.getElementById('formUbicacion');
    const formData = new FormData(form);
    const mensajeDiv = document.getElementById('mensajeRegistro');

    fetch('guardar_destino.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        mensajeDiv.style.color = data.success ? '#4CAF50' : '#ff4d4d';
        mensajeDiv.textContent = data.message;
        if (data.success) {
            form.reset();
            document.getElementById('ciudad').innerHTML = '<option value="">Primero seleccione un país</option>';
            document.getElementById('ciudad').disabled = true;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mensajeDiv.style.color = '#ff4d4d';
        mensajeDiv.textContent = 'Error de conexión al guardar';
    });
}