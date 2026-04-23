<?php
// GUARDAR VIAJE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_usuario'])) {
    include('db.php');
    
    $nombre = $_POST['nombre_usuario'];
    $id_pais = $_POST['id_pais'];
    $id_ciudad = $_POST['id_ciudad'];

    $query = "INSERT INTO registros_viajes (nombre_usuario, id_pais, id_ciudad) VALUES ('$nombre', '$id_pais', '$id_ciudad')";
    $resultado = mysqli_query($conexion, $query);

    if ($resultado) {
        header("Location: index.php?success=1&guardado=1");
    } else {
        header("Location: index.php?success=1&error_viaje=1");
    }
    exit();
}

$isLogged = isset($_GET['success']) && $_GET['success'] == '1';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>App Web Interactiva</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

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

            <h1>Bienvenido 👋</h1>
            <p>Sesión validada exitosamente.</p>

            <!-- BOTONES -->
            <div class="section">
                <button onclick="cambiarColor()">Cambiar Color</button>
                <button onclick="mostrarAlerta()">Alerta JS</button>
            </div>

            <!-- TAREAS -->
            <div class="section">
                <input type="text" id="taskInput" placeholder="Nueva tarea">
                <button onclick="addTask()">Agregar</button>
                <ul id="taskList"></ul>
            </div>

            <!-- OPACIDAD -->
            <div class="section">
                <label>Opacidad de la tarjeta:</label>
                <input type="range" min="10" max="100" value="100" oninput="updateOpacidad(this.value)">
                <span id="value">1</span>
            </div>

            <!-- MODO OSCURO -->
            <div class="section">
                <label class="switch">
                    <input type="checkbox" onchange="toggleModo(this)">
                    <span class="slider"></span>
                </label>
                <p>Modo oscuro</p>
            </div>

            <!-- FORMULARIO REGISTRO DE UBICACIÓN -->
            <div class="section">
                <h3>Registro de Ubicación</h3>

                <?php if(isset($_GET['guardado'])): ?>
                    <p style="color: #00ffaa; font-size: 14px;">✅ Destino guardado exitosamente.</p>
                <?php endif; ?>
                <?php if(isset($_GET['error_viaje'])): ?>
                    <p style="color: #ff4d4d; font-size: 14px;">❌ Error al guardar.</p>
                <?php endif; ?>

                <form action="index.php?success=1" method="POST">

                    <div class="form-row">
                        <label>Nombre usuario</label>
                        <input type="text" name="nombre_usuario" required>
                    </div>

                    <div class="form-row">
                        <label>Países</label>
                        <?php
                        include('db.php');
                        $query = "SELECT * FROM paises";
                        $resultado = mysqli_query($conexion, $query);
                        ?>
                        <select name="id_pais" id="selectPais" onchange="cargarCiudades(this.value)" required>
                            <option value="">  Seleccione un país </option>
                            <?php while($fila = mysqli_fetch_assoc($resultado)): ?>
                                <option value="<?= $fila['id'] ?>"><?= $fila['nombre'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-row">
                        <label>Ciudad</label>
                        <select name="id_ciudad" id="selectCiudad" required>
                            <option value=""> Seleccione una ciudad </option>
                        </select>
                    </div>

                    <?php
                    $queryCiudades = "SELECT * FROM ciudades";
                    $resultadoCiudades = mysqli_query($conexion, $queryCiudades);
                    $todasCiudades = [];
                    while ($fila = mysqli_fetch_assoc($resultadoCiudades)) {
                        $todasCiudades[] = $fila;
                    }
                    ?>
                    <script>
                        const ciudadesPorPais = <?= json_encode($todasCiudades) ?>;
                    </script>

                    <div class="form-row justify-end">
                        <button type="submit" class="btn-guardar">Guardar</button>
                    </div>

                </form>
            </div>

            <!-- BANNER VIDEO -->
            <div class="banner">
                <iframe
                    src="https://www.youtube.com/embed/VPvY1jX48zU?autoplay=1&mute=1&loop=1&playlist=VPvY1jX48zU"
                    frameborder="0"
                    allow="autoplay; encrypted-media"
                    allowfullscreen>
                </iframe>
            </div>

            <br>
            <a href="index.php" style="color: white; font-weight: bold; text-decoration: none;">Cerrar Sesión</a>

        </div>
    </div>

    <!-- MODAL -->
    <div id="modal-overlay" class="modal-overlay hidden" onclick="cerrarModal()">
        <div class="modal-box" onclick="event.stopPropagation()">
            <p>Hola 👋 Esto es JavaScript!</p>
            <button onclick="cerrarModal()">Aceptar</button>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        <p>✌️ Desarrollado por <strong>Samuel Flórez</strong></p>
        <div class="footer-links">
            <a href="https://mail.google.com/mail/?view=cm&to=samuelflorezgrisalez@gmail.com" target="_blank">📧 Contacto</a>
        </div>
        <span class="footer-label">App Web Interactiva © 2026</span>
    </footer>

<?php endif; ?>

<script src="script.js"></script>
</body>
</html>