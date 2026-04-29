<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Web Interactiva</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php
// Verificar si el usuario está logueado
$isLogged = isset($_GET['success']) && $_GET['success'] == '1';
?>

<?php if (!$isLogged): ?>


<div class="container" id="login-container">
    <div class="card">
        <h1>Iniciar Sesión</h1>
        <p>Accede con tus credenciales de la base de datos.</p>

        <form action="login.php" method="POST">
            <input type="text"     name="username" placeholder="Usuario"    required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit" style="width:100%; margin-top:6px;">Ingresar</button>
        </form>

        <?php if (isset($_GET['error'])): ?>
            <p style="color:#ff4d4d; font-size:0.85rem; margin-top:12px; text-align:center;">
                ⚠️ Credenciales incorrectas. Intenta de nuevo.
            </p>
        <?php endif; ?>
    </div>
</div>

<?php else: ?>


<div class="banner">
    <iframe
        width="2000" height="699"
        src="https://www.youtube.com/embed/5l-NxZB9zFo?autoplay=1&mute=1&loop=1&playlist=5l-NxZB9zFo&controls=0&showinfo=0"
        title="Video Loop Bienvenidos"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen>
    </iframe>
    <div class="banner-overlay" >
        <h1>🌍 App Web <span>Interactiva</span></h1>
    </div>
</div>


<div class="container" id="dashboard">
    <div class="card" id="dashboard-card">

        <h1>Bienvenido 👋</h1>
        <p>Sesión validada exitosamente.</p>

        <!-- Botones JS -->
        <div class="section">
            <label>Acciones rápidas</label>
            <button onclick="cambiarColor()">🎨 Cambiar Color</button>
            <button onclick="mostrarAlerta()">💬 Alerta JS</button>
        </div>

        <!-- Lista de tareas -->
        <div class="section">
            <label>Lista de tareas</label>
            <input type="text" id="taskInput" placeholder="Escribe una nueva tarea...">
            <button onclick="addTask()">➕ Agregar</button>
            <ul id="taskList"></ul>
        </div>

        <!-- Slider de opacidad -->
        <div class="section">
            <label>Opacidad de la tarjeta — <span id="value">50</span>%</label>
            <input type="range" min="0" max="100" value="50" oninput="updateValue(this.value)">
        </div>

        <!-- Switch modo oscuro -->
        <div class="section switch-section">
            <label class="switch">
                <input type="checkbox" onchange="toggleModo(this)">
                <span class="slider"></span>
            </label>
            <span>Modo oscuro</span>
        </div>

        <!-- Cerrar sesión -->
        <div style="text-align:center; margin-top:10px;">
            <a href="index.php" class="logout-link">← Cerrar sesión</a>
        </div>


        <?php include('db.php'); ?>

        <div class="section form-registro">
            <h2>📍 Registro de Ubicación</h2>

            <div class="form-row">
                <label>Usuario</label>
                <input type="text" id="nombreUsuario" placeholder="Tu nombre completo">
            </div>

            <div class="form-row">
                <label>País</label>
                <select id="selectPais" onchange="cargarCiudades(this.value)">
                    <option value="">-- Selecciona un país --</option>
                    <?php
                        $res = mysqli_query($conexion, "SELECT id, nombre FROM paises ORDER BY nombre ASC");
                        while ($row = mysqli_fetch_assoc($res)):
                    ?>
                        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nombre']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-row">
                <label>Ciudad</label>
                <select id="selectCiudad">
                    <option value="">-- Selecciona una ciudad --</option>
                </select>
            </div>

            <div class="form-row form-row-btn">
                <button onclick="guardarDestino()">💾 Guardar Destino</button>
            </div>

            <p id="msg-registro"></p>
        </div>

    </div><!-- /.card -->
</div><!-- /.container -->

<!-- Modal alerta -->
<div id="modal-alerta" class="modal-overlay hidden">
    <div class="modal-box">
        <p>¡Hola! 👋<br>Esto fue generado con <strong>JavaScript</strong>.</p>
        <button onclick="cerrarAlerta()">Cerrar</button>
    </div>
</div>

<?php endif; ?>

<!-- ══════════════════════════════════
     FOOTER
══════════════════════════════════ -->
<footer>
    <p>
        Desarrollado por <strong>LuisMi</strong> · Semana 12 ·
        <a href="felilconmifamiliagmail.com">✉️ Contacto</a> ·
        <a href="https://github.com/Luismiguelrestrepo" target="_blank">GitHub</a>
    </p>
</footer>

<script src="script.js"></script>
</body>
</html>
