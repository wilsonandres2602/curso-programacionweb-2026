<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Web Interactiva</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
$isLogged = isset($_GET['success']) && $_GET['success'] == '1';
?>

<?php if (!$isLogged): ?>
<!-- ============================================================
     PANTALLA DE LOGIN
     ============================================================ -->
<div class="container">
    <div class="card" id="mainCard">
        <h2>🔐 Iniciar Sesión</h2>
        <p style="color:var(--text-soft); font-size:13px; margin-bottom:18px;">
            Ingresa tus credenciales para continuar
        </p>
        <form action="login.php" method="POST">
            <input type="text"     name="username" placeholder="👤  Usuario"    required>
            <input type="password" name="password" placeholder="🔑  Contraseña" required>
            <!-- RÚBRICA #1: botón con animación hover -->
            <button type="submit">Ingresar</button>
        </form>
        <?php if (isset($_GET['error'])): ?>
            <p style="color:#e05252; font-size:13px; margin-top:12px;">
                ⚠️ Credenciales incorrectas. Intenta de nuevo.
            </p>
        <?php endif; ?>
    </div>
</div>


<?php else: ?>
<!-- ============================================================
     DASHBOARD
     ============================================================ -->

<!-- RÚBRICA #4 — BANNER multimedia -->
<div class="banner">
    <iframe
        src="https://www.youtube.com/embed/ScMzIvxBSi4?autoplay=1&mute=1&loop=1&playlist=ScMzIvxBSi4&controls=0&showinfo=0&rel=0"
        allow="autoplay; encrypted-media"
        allowfullscreen
        title="Banner decorativo">
    </iframe>
    <span class="banner-label">✦ App Web Interactiva ✦</span>
</div>


<!-- ============================================================
     BARRA DE CONTROLES GLOBALES — FUERA de la card
     RÚBRICA #1: slider controla opacidad de #mainCard.
     Al estar fuera, el slider SIEMPRE es visible aunque
     la card baje a opacidad 0.1.
     ============================================================ -->
<div class="controls-bar">
    <div class="ctrl-item">
        <span class="ctrl-label">🔆 Opacidad del Panel</span>
        <div class="ctrl-slider-row">
            <input type="range" id="opacitySlider" min="0" max="100" value="100"
                   oninput="updateValue(this.value)">
            <span class="slider-value" id="value">100%</span>
        </div>
    </div>
    <div class="ctrl-divider"></div>
    <div class="ctrl-item">
        <span class="ctrl-label" id="modeLabel">☀️ Modo Claro</span>
        <label class="switch">
            <input type="checkbox" id="darkToggle" onchange="toggleModo(this)">
            <span class="switch-track"></span>
        </label>
    </div>
</div>


<!-- Dashboard principal -->
<div class="container">
    <!-- id="mainCard" es el target del slider de opacidad (RÚBRICA #1) -->
    <div class="card" id="mainCard">

        <h1>Bienvenido 👋</h1>
        <p>Sesión validada exitosamente.</p>

        <!-- ================================================
             Botones de acción — RÚBRICA #1: animados en hover
             ================================================ -->
        <div class="section">
            <label>Acciones rápidas</label>
            <button onclick="cambiarColor()">🎨 Cambiar Color</button>
            <button onclick="mostrarAlerta()">🔔 Mostrar Alerta JS</button>
        </div>

        <!-- ================================================
             Lista de tareas
             ================================================ -->
        <div class="section">
            <label>Lista de Tareas</label>
            <input type="text" id="taskInput" placeholder="Escribe una tarea y presiona Enter...">
            <button onclick="addTask()">➕ Agregar Tarea</button>
            <ul id="taskList"></ul>
        </div>


        <!-- ================================================
             RÚBRICA #2 y #3 — Formulario de Registro de Ubicación
             ================================================ -->
        <?php include('db.php'); ?>

        <div class="form-ubicacion">
            <h3>📍 Registro de Ubicación</h3>

            <!-- Textbox nombre (RÚBRICA #3) -->
            <input type="text" id="inputNombre" placeholder="👤  Nombre de usuario">

            <!-- ============================================
                 RÚBRICA #3 — Selector de País PERSONALIZADO
                 Picker visual con búsqueda integrada.
                 El <select> oculto es actualizado por JS.
                 ============================================ -->
            <div class="country-picker" id="countryPicker">
                <div class="picker-trigger" id="pickerTrigger" onclick="togglePicker()">
                    <span class="picker-icon">🌎</span>
                    <span class="picker-text" id="pickerText">Selecciona un país</span>
                    <span class="picker-arrow" id="pickerArrow">▾</span>
                </div>
                <div class="picker-panel" id="pickerPanel">
                    <div class="picker-search-wrap">
                        <span class="search-icon">🔍</span>
                        <input type="text" id="countrySearch"
                               placeholder="Buscar país..."
                               oninput="filtrarPaises(this.value)"
                               autocomplete="off">
                    </div>
                    <div class="picker-list" id="pickerList"></div>
                </div>
                <!-- Select oculto: PHP lo llena, JS lo sincroniza -->
                <select id="selectPais" style="display:none;">
                    <option value="">Selecciona un país</option>
                    <?php
                        $res = mysqli_query($conexion, "SELECT id, nombre FROM paises ORDER BY nombre");
                        while ($fila = mysqli_fetch_assoc($res)) {
                            echo '<option value="' . $fila['id'] . '">'
                               . htmlspecialchars($fila['nombre'])
                               . '</option>';
                        }
                    ?>
                </select>
            </div>

            <!-- Select de ciudades — se llena dinámicamente -->
            <div class="select-wrapper">
                <select id="selectCiudad" disabled>
                    <option value="">🏙️ Selecciona un país primero</option>
                </select>
            </div>

            <!-- RÚBRICA #1 + #3: botón animado + guardar en BD -->
            <button class="btn-gold" onclick="guardarDestino()">💾 Guardar Destino</button>
            <p id="mensaje-guardado" style="font-size:13px; margin-top:8px; min-height:20px;"></p>
        </div>

        <a href="index.php" class="logout-link">← Cerrar Sesión</a>

    </div><!-- /card -->
</div><!-- /container -->


<!-- ============================================================
     MODAL de confirmación — RÚBRICA #3
     ============================================================ -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal-box">
        <span class="modal-icon">✅</span>
        <h2>¡Destino Guardado!</h2>
        <p>El siguiente registro fue almacenado en la base de datos.</p>
        <div class="modal-data">
            <div class="data-row">
                <span class="data-label">Usuario</span>
                <span class="data-value" id="modal-usuario">—</span>
            </div>
            <div class="data-row">
                <span class="data-label">País</span>
                <span class="data-value" id="modal-pais">—</span>
            </div>
            <div class="data-row">
                <span class="data-label">Ciudad</span>
                <span class="data-value" id="modal-ciudad">—</span>
            </div>
        </div>
        <button onclick="cerrarModal()">Aceptar</button>
    </div>
</div>


<!-- ============================================================
     RÚBRICA #4 — FOOTER con position: relative
     Se adapta automáticamente al modo oscuro/claro.
     ============================================================ -->
<footer>
    &copy; <?php echo date('Y'); ?> App Web Interactiva &nbsp;|&nbsp;
    Desarrollado por <strong>Maria Alejandra</strong> &nbsp;|&nbsp;
    <a href="mailto:tucorreo@email.com">📧 mariaalejandra32@gmail.com</a>
</footer>

<?php endif; ?>

<script src="script.js"></script>
</body>
</html>
