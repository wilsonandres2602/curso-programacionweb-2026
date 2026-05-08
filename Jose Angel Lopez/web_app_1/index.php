<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>App Web Interactiva</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
include('db.php');
$isLogged = isset($_GET['success']) && $_GET['success'] == '1';

$paises   = mysqli_query($conexion, "SELECT * FROM paises ORDER BY nombre");
$ciudades = mysqli_query($conexion, "SELECT * FROM ciudades ORDER BY nombre");
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

    <div class="container" id="dashboard">
        <div class="card">

            <!-- BANNER VIDEO -->
             
            <div class="banner">
                <iframe
                    src="https://www.youtube.com/embed/pAgnJDJN4VA?autoplay=1&mute=0" 
                    title="Banner de bienvenida"
                    frameborder="0"
                    allow="autoplay; encrypted-media"
                    allowfullscreen>
                </iframe>
            </div>

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
                <input type="range" min="0" max="100" oninput="updateValue(this.value)">
                <span id="value">100</span>
            </div>

            <div class="section">
                <label class="switch">
                    <input type="checkbox" onchange="toggleModo(this)">
                    <span class="slider"></span>
                </label>
                <p>Modo oscuro</p>
            </div>

            <!-- SECCIÓN: REGISTRO DE UBICACIÓN -->
            <div class="section">
                <h3>📍 Registro de Ubicación</h3>

                <?php if(isset($_GET['guardado'])): ?>
                    <p style="color: #90ee90; font-size: 14px;">✅ ¡Destino guardado correctamente!</p>
                <?php elseif(isset($_GET['error_destino'])): ?>
                    <p style="color: #ff4d4d; font-size: 14px;">❌ Error al guardar. Intenta de nuevo.</p>
                <?php endif; ?>

                <form action="guardar_destino.php" method="POST">
                    <input type="text" name="nombre_usuario" placeholder="Nombre de usuario" required style="color: #333;">

                    <select name="id_pais" id="selectPais" onchange="filtrarCiudades()" required style="padding:12px; border-radius:8px; border:none; width:100%; margin:10px 0; box-sizing:border-box; color:#333;">
                        <option value="">-- Selecciona un país --</option>
                        <?php while($pais = mysqli_fetch_assoc($paises)): ?>
                            <option value="<?= $pais['id'] ?>"><?= $pais['nombre'] ?></option>
                        <?php endwhile; ?>
                    </select>

                    <select name="id_ciudad" id="selectCiudad" required style="padding:12px; border-radius:8px; border:none; width:100%; margin:10px 0; box-sizing:border-box; color:#333;">
                        <option value="">-- Selecciona una ciudad --</option>
                        <?php while($ciudad = mysqli_fetch_assoc($ciudades)): ?>
                            <option value="<?= $ciudad['id'] ?>" data-pais="<?= $ciudad['id_pais'] ?>">
                                <?= $ciudad['nombre'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <button type="submit">💾 Guardar destino</button>
                </form>
            </div>

            <br>
            <a href="index.php" style="color: white; font-weight: bold; text-decoration: none;">Cerrar Sesión</a>

        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
    <div class="footer-container">
        <p>&copy; 2026 | <strong>Jose Angel Lopez</strong></p>
        
        <p>
            <a href="mailto:jangell@correo.iue.edu.com">
                <i class="fas fa-envelope"></i> jangell@correo.iue.edu.com
            </a>
        </p>

        <p>
            <a href="https://github.com/wilsonandres2602/curso-programacionweb-2026/tree/main/web_app_1_jose_angel" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-github"></i> Ver Proyecto en GitHub
            </a>
        </p>
    </div>
</footer>

<?php endif; ?>

<!-- MODAL ALERTA -->
<div id="modalAlerta" class="modal-overlay" onclick="cerrarAlertaFuera(event)">
    <div class="modal-box">
        <p>¡Hola! Esta es una función de JavaScript.</p>
        <button onclick="cerrarAlerta()">Cerrar</button>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>