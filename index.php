<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>App Web Interactiva</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
$isLogged = isset($_GET['success']) && $_GET['success'] == '1';
?>

<?php if (!$isLogged): ?>
    <div class="container" id="login-container">
        <div class="card">
            <h2>Login (PHP + MySQL)</h2>
            <form action="login.php" method="POST">
                <input type="text" name="username" placeholder="Usuario" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit">Ingresar</button>
            </form>
            <?php if(isset($_GET['error'])): ?>
                <p style="color: #ff4d4d; font-size: 14px; margin-top: 10px;">Credenciales incorrectas</p>
            <?php endif; ?>
        </div>
    </div>

<?php else: ?>
    <div class="dashboard-wrapper">
        <div class="card" id="mainCard">

            <!-- PUNTO 4: Banner arriba del Bienvenido -->
            <div class="banner-dashboard">
                <span class="banner-emoji">🌍</span>
                <span class="banner-emoji">✈️</span>
                <div class="banner-dashboard-overlay">
                    <h2>App Web Interactiva</h2>
                </div>
            </div>

            <div style="padding: 0 20px;">
                <h1>Bienvenido 👋</h1>
                <p>Sesión validada exitosamente.</p>

                <!-- Botones -->
                <div class="section">
                    <button onclick="cambiarColor()">Cambiar Color</button>
                    <button onclick="mostrarAlerta()">Alerta JS</button>
                </div>

                <!-- Tareas -->
                <div class="section">
                    <input type="text" id="taskInput" placeholder="Nueva tarea">
                    <button onclick="addTask()">Agregar</button>
                    <ul id="taskList"></ul>
                </div>

                <!-- PUNTO 1: Slider cambia opacidad de la card -->
                <div class="section">
                    <label>Opacidad: <span id="value">50</span>%</label>
                    <input type="range" id="slider" min="0" max="100" value="50" oninput="updateValue(this.value)">
                </div>

                <!-- Modo oscuro -->
                <div class="section">
                    <label class="switch">
                        <input type="checkbox" onchange="toggleModo(this)">
                        <span class="slider"></span>
                    </label>
                    <p>Modo oscuro</p>
                </div>

                <!-- Ir a registro -->
                <div class="section">
                    <a href="registro.php" class="btn-link">🌍 Ir a Registro de Ubicación</a>
                </div>

                <br>
                <a href="index.php" style="color: white; font-weight: bold; text-decoration: none;">Cerrar Sesión</a>
                <br><br>
            </div>
        </div>

        <!-- PUNTO 4: Footer -->
        <footer class="footer">
            <p>✨ <strong>Mariana Arenas</strong> &nbsp;|&nbsp; Proyecto Web 2025</p>
            <p>
                <a href="mailto:tucorreo@email.com">📧 Contacto</a>
                &nbsp;&nbsp;
                <a href="#">🔗 LinkedIn</a>
            </p>
        </footer>
    </div>
<?php endif; ?>

<!-- MODAL -->
<div class="modal-overlay" id="modalOverlay" onclick="cerrarModal(event)">
    <div class="modal-box" id="modalBox">
        <p id="modalTexto">¡Hola! Esta es una función de JavaScript.</p>
        <button class="modal-close" onclick="cerrarModal()">Cerrar</button>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>