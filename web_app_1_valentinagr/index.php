<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Proyecto Web</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
$isLogged = isset($_GET['success']) && $_GET['success'] == '1';
?>

<?php if (!$isLogged): ?>
    <div class="container" id="login-container">
        <div class="card">
            <h2>Inicio de sesión</h2>

            <form action="login.php" method="POST">
                <input type="text" name="username" placeholder="Usuario" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit">Ingresar</button>
            </form>

            <?php if(isset($_GET['error'])): ?>
                <p class="error-text">Credenciales incorrectas</p>
            <?php endif; ?>
        </div>
    </div>

<?php else: ?>
    <div class="dashboard-wrapper">
        <div class="card" id="mainCard">

            <div class="banner-dashboard">
                <div class="banner-dashboard-overlay">
                    <h2>Mi Proyecto Web</h2>
                </div>
            </div>

            <div class="card-content">

                <div class="bienvenida-box">
                    <h1>Bienvenido</h1>
                    <p>Sesión validada exitosamente.</p>
                </div>

                <div class="section botones-superiores">
                    <button onclick="cambiarColor()">Cambiar Color</button>
                    <button onclick="mostrarAlerta()">Alerta JS</button>
                </div>

                <div class="section">
                    <input type="text" id="taskInput" placeholder="Nueva tarea">
                    <button onclick="addTask()">Agregar</button>
                    <ul id="taskList"></ul>
                </div>

                <div class="section">
                    <label>Opacidad: <span id="value">50</span>%</label>
                    <input type="range" id="slider" min="0" max="100" value="50" oninput="updateValue(this.value)">
                </div>

                <div class="acciones-inferiores">
                    <div class="registro-area">
                        <a href="registro.php" class="btn-link">Registro de Ubicación</a>
                    </div>

                    <div class="logout-area">
                        <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
                    </div>
                </div>

                <div class="modo-oscuro-box">
                    <label class="switch">
                        <input type="checkbox" onchange="toggleModo(this)">
                        <span class="slider"></span>
                    </label>
                    <p>Modo oscuro</p>
                </div>

            </div>
        </div>

        <footer class="footer">
            <p><strong>Valentina Gaviria</strong> | Proyecto Web 2026</p>
            <p class="social-links">
                <a href="https://www.instagram.com/vgr1029/" target="_blank" title="Instagram">📷</a>
                <a href="mailto:vgaviria@correo.iue.edu.co" title="Correo">✉️</a>
                <a href="https://wa.me/573001112233" target="_blank" title="WhatsApp">💬</a>
            </p>
        </footer>
    </div>
<?php endif; ?>

<div class="modal-overlay" id="modalOverlay" onclick="cerrarModal(event)">
    <div class="modal-box" id="modalBox">
        <p id="modalTexto">¡Hola! Esta es una función de JavaScript.</p>
        <button class="modal-close" onclick="cerrarModal()">Cerrar</button>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>