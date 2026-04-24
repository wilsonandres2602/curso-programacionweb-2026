
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>App Web Interactiva</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="banner">
  <div class="banner-contenido">
    <h1>Funciones y Miscelanios</h1>
    <p>Exploremos y Aprendamos</p>
  </div>
</header>

<?php
// Verificamos si el usuario ya se logueó correctamente
$isLogged = isset($_GET['success']) && $_GET['success'] == '1';
?>

<!--Pegar-->
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
            <br>
            <a href="index.php" style="color: white; font-weight: bold; text-decoration: none;">Cerrar Sesión</a>
        </div>
    </div>

<?php
    include("db.php");
    $paises = mysqli_query($conexion, "SELECT * FROM paises");
            
    if (isset($_POST['guardar'])) {
        $usuario = $_POST['usuario'];
        $pais = $_POST['pais'];
        $ciudad = $_POST['ciudad'];

        $query = "INSERT INTO registros_viajes (usuario, id_pais, id_ciudad) VALUES ('$usuario', '$pais', '$ciudad')";
        $resultado = mysqli_query($conexion, $query);

        if ($resultado) {
            echo "<script>alert('Destino guardado correctamente');</script>";
        } else {
            echo "<script>alert('Error al guardar');</script>";
        }                          
    }
?> 
    <div class="container" id="viajes">
        <div class="card">
                       
            <div class="section">
                <h3>Registro de Ubicación</h3>

                <form action="#" method="POST">
                    <input type="text" name="usuario" placeholder="Nombre de usuario" required>
                    <!-- SELECT PAISES -->
                    <select name="pais" id="pais" onchange="cargarCiudades()" required>
                        <option value="">Seleccione un país</option>
                        <?php while($pais = mysqli_fetch_assoc($paises)) { ?>
                            <option value="<?php echo $pais['id']; ?>">
                                <?php echo $pais['nombre']; ?>
                            </option>
                        <?php } ?>
                    </select>
                    <!-- SELECT CIUDADES -->
                    <select name="ciudad" id="ciudad" required>
                        <option value="">Seleccione una ciudad</option>
                    </select>
                    <!-- GUARDAR DESTINO -->
                    <button type="submit" name="guardar">Guardar Destino</button>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?> 

<script src="script.js"></script>

<footer class="footer">
    <p>&copy; 2025 Web-App-1</p>
    <a href="#">Contacto</a>
</footer>

</body>
</html>