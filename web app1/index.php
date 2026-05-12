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
                <input type="range" min="0" max="100" value="50" oninput="updateValue(this.value)">
                <span id="value">50</span>
            </div>
 
            <div class="section">
                <label class="switch">
                    <input type="checkbox" onchange="toggleModo(this)">
                    <span class="slider"></span>
                </label>
                <p>Modo oscuro</p>
            </div>

            <!-- NUEVO FORMULARIO DE REGISTRO DE UBICACIÓN -->
            <div class="section">
                <h3>📍 Registro de Ubicación</h3>
                <form action="guardar_destino.php" method="POST">
                    <input type="text" name="nombre_usuario" placeholder="Nombre de usuario" required>
                    
                    <select name="pais" id="pais" onchange="cargarCiudades()" required>
                        <option value="">Selecciona un país</option>
                        <?php
                        $query_paises = "SELECT id, nombre FROM paises";
                        $resultado_paises = mysqli_query($conexion, $query_paises);
                        while ($pais = mysqli_fetch_assoc($resultado_paises)) {
                            echo '<option value="' . $pais['id'] . '">' . $pais['nombre'] . '</option>';
                        }
                        ?>
                    </select>
                    
                    <select name="ciudad" id="ciudad" required>
                        <option value="">Selecciona una ciudad</option>
                    </select>
                    
                    <button type="submit">Guardar Destino</button>
                </form>
                
                <?php if(isset($_GET['destino_guardado'])): ?>
                    <p style="color: #4dff4d; font-size: 14px; margin-top: 10px;">✓ Destino guardado exitosamente</p>
                <?php endif; ?>
                <?php if(isset($_GET['error_destino'])): ?>
                    <p style="color: #ff4d4d; font-size: 14px; margin-top: 10px;">Error al guardar destino</p>
                <?php endif; ?>
            </div>

            <!-- BANNER -->
            <div class="banner-section">
                <img src="https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=800&h=300&fit=crop" 
                     alt="Banner de viajes" 
                     class="banner-image">
                <div class="banner-text">
                    <h2>Explora el Mundo 🌍</h2>
                    <p>Registra tus destinos favoritos</p>
                </div>
            </div>
            
            <br>
            <a href="index.php" style="color: white; font-weight: bold; text-decoration: none;">Cerrar Sesión</a>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2026 Mi Proyecto Web - Todos los derechos reservados</p>
            <p>Desarrollado por <strong>Stevan Galeano Carrillo</strong></p>
            <a href="mailto:sagaleano10@gmail.com" class="footer-link">✉️ Contacto</a>
        </div>
    </footer>

<?php 
mysqli_close($conexion);
endif; 
?>
 
<script src="script.js"></script>
</body>
</html>