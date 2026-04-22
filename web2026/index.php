<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'conexion.php';

$mensaje = "";
$tipo_mensaje = "";

// ===== GUARDAR DESTINO =====
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar_destino'])) {
    $nombre_usuario = $conn->real_escape_string($_POST['nombre_usuario']);
    $id_pais        = (int) $_POST['id_pais'];
    $id_ciudad      = (int) $_POST['id_ciudad'];

    $sql = "INSERT INTO registros_viajes (nombre_usuario, id_pais, id_ciudad)
            VALUES ('$nombre_usuario', $id_pais, $id_ciudad)";

    if ($conn->query($sql)) {
        $mensaje = "¡Destino guardado correctamente! 🌍";
        $tipo_mensaje = "ok";
    } else {
        $mensaje = "Error al guardar: " . $conn->error;
        $tipo_mensaje = "error";
    }
}

// ===== CARGAR PAÍSES =====
$paises = $conn->query("SELECT id, nombre FROM paises ORDER BY nombre");

// ===== CARGAR CIUDADES (todas, el JS filtra por país) =====
$ciudades = $conn->query("SELECT id, nombre, id_pais FROM ciudades ORDER BY nombre");
$ciudades_array = [];
while ($c = $ciudades->fetch_assoc()) {
    $ciudades_array[] = $c;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - Mi Proyecto</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>

<!-- ===== BANNER ===== -->
<div class="banner">
    <div class="banner-overlay">
        <h1>🌐 Mi Proyecto Web</h1>
        <p>Tecnología · Diseño · Viajes</p>
    </div>
</div>

<!-- ===== CONTENIDO ===== -->
<div class="main-content">

    <h2 style="margin-bottom:20px;">
        Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?> 🎉
    </h2>

    <!-- ===== CARD PRINCIPAL (su opacidad la controla el slider) ===== -->
    <div class="card" id="card-principal">
        <h2>⚙️ Panel de Tareas y Volumen</h2>

        <!-- Formulario de tarea -->
        <div class="form-group">
            <label for="tarea">Nueva Tarea:</label>
            <input type="text" id="tarea" placeholder="Escribe una tarea...">
        </div>

        <button onclick="alert('Tarea: ' + document.getElementById('tarea').value || 'vacía')">
            ➕ Agregar Tarea
        </button>

        <hr style="margin: 20px 0; border-color: #eee;">

        <!-- Slider de volumen que cambia la opacidad -->
        <div class="slider-container">
            <label for="volumen">
                🔊 Volumen / Opacidad de la Card:
                <span id="volumen-valor">50%  (opacidad: 0.55)</span>
            </label>
            <input type="range" id="volumen" name="volumen" min="0" max="100" value="50">
        </div>

        <p style="font-size:0.85rem; opacity:0.7; margin-top:8px;">
            💡 Mueve el slider para cambiar la opacidad de esta card (0.1 → 1.0)
        </p>
    </div>

    <!-- ===== FORMULARIO REGISTRO DE UBICACIÓN ===== -->
    <div class="card">
        <h2>📍 Registro de Ubicación</h2>

        <?php if ($mensaje): ?>
            <div class="mensaje-<?php echo $tipo_mensaje; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <!-- Nombre de usuario -->
            <div class="form-group">
                <label for="nombre_usuario">Nombre de Usuario:</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario"
                       placeholder="Tu nombre..." required>
            </div>

            <!-- Select de País (llenado desde BD con PHP) -->
            <div class="form-group">
                <label for="id_pais">País:</label>
                <select id="id_pais" name="id_pais" required onchange="filtrarCiudades()">
                    <option value="">-- Selecciona un país --</option>
                    <?php while ($p = $paises->fetch_assoc()): ?>
                        <option value="<?php echo $p['id']; ?>">
                            <?php echo htmlspecialchars($p['nombre']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Select de Ciudad (filtrado dinámicamente con JS) -->
            <div class="form-group">
                <label for="id_ciudad">Ciudad:</label>
                <select id="id_ciudad" name="id_ciudad" required>
                    <option value="">-- Primero selecciona un país --</option>
                </select>
            </div>

            <button type="submit" name="guardar_destino">🌍 Guardar Destino</button>
        </form>
    </div>

    <a href="logout.php" class="logout-link">🔒 Cerrar sesión</a>
</div>

<!-- ===== FOOTER ===== -->
<footer>
    <p>
        © <?php echo date('Y'); ?> Mi Proyecto Web &nbsp;|&nbsp;
        Desarrollado por <?php echo htmlspecialchars($_SESSION['username']); ?>
        &nbsp;|&nbsp;
        <a href="mailto:contacto@miproyecto.com">📧 Contacto</a>
    </p>
</footer>

<!-- ===== JS: FILTRAR CIUDADES POR PAÍS ===== -->
<script>
    // Todas las ciudades vienen desde PHP embebido en JSON
    const todasLasCiudades = <?php echo json_encode($ciudades_array); ?>;

    function filtrarCiudades() {
        const idPais = parseInt(document.getElementById("id_pais").value);
        const selectCiudad = document.getElementById("id_ciudad");

        // Limpiar opciones anteriores
        selectCiudad.innerHTML = '<option value="">-- Selecciona una ciudad --</option>';

        if (!idPais) {
            selectCiudad.innerHTML = '<option value="">-- Primero selecciona un país --</option>';
            return;
        }

        // Filtrar y agregar solo las ciudades del país seleccionado
        todasLasCiudades
            .filter(c => c.id_pais == idPais)
            .forEach(c => {
                const opt = document.createElement("option");
                opt.value = c.id;
                opt.textContent = c.nombre;
                selectCiudad.appendChild(opt);
            });
    }
</script>

</body>
</html>
