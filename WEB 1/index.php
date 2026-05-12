<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('db.php');
$isLogged = isset($_GET['success']) && $_GET['success'] == '1';

// Obtener países para el select (si está logueado)
$paises = [];
if ($isLogged) {
    $result = mysqli_query($conexion, "SELECT id, nombre FROM paises ORDER BY nombre");
    while ($row = mysqli_fetch_assoc($result)) {
        $paises[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>App Web Interactiva</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php if (!$isLogged): ?>
    <!-- Formulario de Login -->
    <div class="container" id="login-container">
        <div class="card">
            <h2>Login (PHP + MySQL)</h2>
            <form action="login.php" method="POST">
                <input type="text" name="username" placeholder="Usuario" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit">Ingresar</button>
            </form>
            <?php if (isset($_GET['error'])): ?>
                <p style="color: #ff4d4d; font-size: 14px; margin-top: 10px;">
                    Credenciales incorrectas
                </p>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <!-- Banner multimedia (imagen o video) -->
    <div class="banner">
        <!-- Ejemplo con video de YouTube incrustado (puedes cambiarlo por una imagen) -->
        <iframe width="100%" height="200" src="https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=0&controls=0&mute=1&loop=1&playlist=dQw4w9WgXcQ" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        <!-- Alternativa con imagen: <img src="banner.jpg" alt="Banner"> -->
    </div>

    <!-- Dashboard principal -->
    <div class="container" id="dashboard">
        <div class="card">
            <h1>Bienvenido 👋</h1>
            <p>Sesión validada exitosamente.</p>

            <div class="section">
                <button onclick="cambiarColor()">Cambiar Color</button>
                <button onclick="mostrarAlerta()">Alerta JS</button>
            </div>

            <div class="section">
                <input type="text" id="taskInput" placeholder="Nueva tarea">
                <button onclick="addTask()">Agregar</button>
                <ul id="taskList"></ul>
            </div>

            <div class="section">
                <label>Volumen:</label>
                <input type="range" min="0" max="100" value="100" oninput="updateValue(this.value)">
                <span id="value">100</span>
            </div>

            <div class="section">
                <label class="switch">
                    <input type="checkbox" onchange="toggleModo(this)">
                    <span class="slider"></span>
                </label>
                <p>Modo oscuro</p>
            </div>

            <br>
            <a href="index.php" style="color: white; font-weight: bold; text-decoration: none;">
                Cerrar Sesión
            </a>
        </div>
    </div>

    <!-- NUEVA SECCIÓN: Registro de Ubicación -->
    <div class="container" id="registro-ubicacion">
        <div class="card">
            <h2>Registro de Ubicación</h2>
            <form id="formUbicacion" onsubmit="guardarDestino(event)">
                <input type="text" name="nombre_usuario" placeholder="Nombre de usuario" required>
                
                <label for="pais">País:</label>
                <select name="id_pais" id="pais" required onchange="cargarCiudades(this.value)">
                    <option value="">Seleccione un país</option>
                    <?php foreach ($paises as $pais): ?>
                        <option value="<?= $pais['id'] ?>"><?= htmlspecialchars($pais['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="ciudad">Ciudad:</label>
                <select name="id_ciudad" id="ciudad" required disabled>
                    <option value="">Primero seleccione un país</option>
                </select>

                <button type="submit">Guardar Destino</button>
            </form>
            <div id="mensajeRegistro" style="margin-top: 10px;"></div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p>© 2026 - Desarrollado por [Tu Nombre] | <a href="mailto:contacto@ejemplo.com">Contacto</a></p>
        </div>
    </footer>
<?php endif; ?>

<script src="script.js"></script>
</body>
</html>