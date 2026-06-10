function cambiarColor() {
    document.body.style.backgroundColor = '#0A0612';
    document.body.style.setProperty('--indigo', '#EC4899');
    document.body.style.setProperty('--cyan', '#F59E0B');
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
        document.getElementById("taskList").appendChild(li);
        input.value = "";
        input.focus();
    }
}

function updateValue(val) {
    document.getElementById("value").innerText = val;
    const card = document.querySelector('.card');
    if (card) {
        const opacidad = 0.025 + (val / 100) * 0.07;
        card.style.backgroundColor = `rgba(255, 255, 255, ${opacidad})`;
    }
}

function toggleModo(el) {
    if (el.checked) {
        document.body.style.backgroundColor = '#020305';
        document.body.style.setProperty('--surface', 'rgba(255,255,255,0.025)');
        document.querySelector('body::before');
    } else {
        document.body.style.backgroundColor = '';
        document.body.style.setProperty('--surface', 'rgba(255,255,255,0.04)');
    }
    const footer = document.querySelector('footer');
    if (footer) footer.style.backgroundColor = el.checked ? 'rgba(0,0,0,0.6)' : '';
}

function cargarCiudades(idPais) {
    const ciudadSelect = document.getElementById('ciudad');
    ciudadSelect.disabled = true;
    ciudadSelect.innerHTML = '<option value="">Cargando ciudades...</option>';

    if (!idPais) {
        ciudadSelect.innerHTML = '<option value="">Primero selecciona un país</option>';
        return;
    }

    fetch(`get_ciudades.php?id_pais=${idPais}`)
        .then(response => response.json())
        .then(data => {
            ciudadSelect.innerHTML = '<option value="">Selecciona una ciudad</option>';
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

    mensajeDiv.className = '';
    mensajeDiv.textContent = 'Guardando...';

    fetch('guardar_destino.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        mensajeDiv.className = data.success ? 'success' : 'error';
        mensajeDiv.textContent = data.message;
        if (data.success) {
            form.reset();
            document.getElementById('ciudad').innerHTML = '<option value="">Primero selecciona un país</option>';
            document.getElementById('ciudad').disabled = true;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mensajeDiv.className = 'error';
        mensajeDiv.textContent = 'Error de conexión al guardar.';
    });
}
