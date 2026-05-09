<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>App Web Interactiva</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
// Verificamos si el usuario ya se logueó correctamente
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
    <div class="container" id="dashboard">
        <div class="card">
            <!-- BANNER DE YOUTUBE -->
            <div style="margin-bottom: 20px; border-radius: 10px; overflow: hidden; height: 180px;">
                <iframe width="100%" height="100%" src="https://www.youtube.com/embed/jfKfPfyJRdk?autoplay=0&controls=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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
                <input type="range" min="10" max="100" value="100" oninput="updateValue(this.value)">
                <span id="value">100</span>
            </div>

            <div class="section">
                <label class="switch">
                    <input type="checkbox" onchange="toggleModo(this)">
                    <span class="slider"></span>
                </label>
                <p>Modo oscuro</p>
            </div>

            <!-- NUEVO FORMULARIO MAESTRO -->
            <div class="section">
                <h2>Registro de Ubicación</h2>
                <form action="guardar_destino.php" method="POST">
                    <input type="text" name="usuario" placeholder="Nombre de usuario" required>
                    
                    <?php
                    // Incluimos la conexión a la base de datos
                    include 'db.php';
                    
                    // Consultamos los países y ciudades
                    $queryPaises = mysqli_query($conexion, "SELECT * FROM paises");
                    $queryCiudades = mysqli_query($conexion, "SELECT * FROM ciudades");
                    ?>
                    
                    <select name="id_pais" required>
                        <option value="">Seleccione un país...</option>
                        <?php while ($pais = mysqli_fetch_assoc($queryPaises)): ?>
                            <option value="<?php echo $pais['id']; ?>"><?php echo $pais['nombre']; ?></option>
                        <?php endwhile; ?>
                    </select>

                    <select name="id_ciudad" required>
                        <option value="">Seleccione una ciudad...</option>
                        <?php while ($ciudad = mysqli_fetch_assoc($queryCiudades)): ?>
                            <option value="<?php echo $ciudad['id']; ?>"><?php echo $ciudad['nombre']; ?></option>
                        <?php endwhile; ?>
                    </select>

                    <button type="submit">Guardar Destino</button>
                </form>
            </div>

            <br>
            <a href="index.php" style="color: white; font-weight: bold; text-decoration: none;">Cerrar Sesión</a>
        </div>
    </div>
<?php endif; ?>

<footer class="footer">
    <p>Desarrollado por Sergio Murillo. <a href="semurillo@correo.iue.edu.co">Contáctame</a></p>
</footer>

<script src="script.js"></script>
</body>
</html>
 