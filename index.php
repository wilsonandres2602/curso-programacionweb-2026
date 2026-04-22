<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>App Web Interactiva</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
 
<?php
// LÍNEA IMPORTANTE: Incluimos el archivo de conexión a la BD
require_once('db.php');

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
            <!-- LÍNEA IMPORTANTE: iframe de YouTube con autoplay y sin controles -->
            <!-- src: URL del embed con el ID del video (Fzcc_R1lQ_Y) -->
            <!-- autoplay=1: reproduce automáticamente -->
            <!-- controls=0: oculta los controles de reproducción -->
            <!-- allowfullscreen: permite pantalla completa -->
            <iframe width="100%" height="400" 
                    src="https://www.youtube.com/embed/Fzcc_R1lQ_Y?autoplay=1&controls=0" 
                    frameborder="0" 
                    allowfullscreen
                    style="border-radius: 12px; margin-bottom: 20px;">
            </iframe>
            
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
            
            <!-- NUEVA SECCIÓN: REGISTRO DE UBICACIÓN -->
            <div class="section">
                <h3>📍 Registro de Ubicación</h3>
                <!-- Formulario para registrar viajes con país y ciudad -->
                <form action="guardar_viaje.php" method="POST">
                    
                    <!-- CAMPO 1: Username (básico) -->
                    <input type="text" name="username" placeholder="Tu nombre de usuario" required>
                    
                    <!-- CAMPO 2: Select dinámico de Países -->
                    <!-- LÍNEA IMPORTANTE: Este select se llena con PHP consultando la BD -->
                    <select name="pais" required>
                        <option value="">Selecciona un país</option>
                        <?php
                            // EXPLICACIÓN: Consultamos la tabla 'paises' de la BD
                            $query_paises = "SELECT id, nombre FROM paises ORDER BY nombre";
                            $resultado_paises = mysqli_query($conexion, $query_paises);
                            
                            // LÍNEA IMPORTANTE: Recorremos cada país y lo mostramos como opción
                            while($pais = mysqli_fetch_assoc($resultado_paises)) {
                                echo "<option value='" . $pais['id'] . "'>" . $pais['nombre'] . "</option>";
                            }
                        ?>
                    </select>
                    
                    <!-- CAMPO 3: Select dinámico de Ciudades -->
                    <!-- LÍNEA IMPORTANTE: Este select se llena con PHP consultando la BD -->
                    <select name="ciudad" required>
                        <option value="">Selecciona una ciudad</option>
                        <?php
                            // EXPLICACIÓN: Consultamos la tabla 'ciudades' de la BD
                            // Para este ejemplo básico, mostramos las primeras ciudades
                            $query_ciudades = "SELECT id, nombre FROM ciudades ORDER BY nombre LIMIT 10";
                            $resultado_ciudades = mysqli_query($conexion, $query_ciudades);
                            
                            // LÍNEA IMPORTANTE: Recorremos cada ciudad y la mostramos como opción
                            while($ciudad = mysqli_fetch_assoc($resultado_ciudades)) {
                                echo "<option value='" . $ciudad['id'] . "'>" . $ciudad['nombre'] . "</option>";
                            }
                        ?>
                    </select>
                    
                    <!-- BOTÓN: Enviar el formulario -->
                    <!-- LÍNEA IMPORTANTE: Al hacer click, envía los datos a guardar_viaje.php -->
                    <button type="submit">Guardar Destino</button>
                </form>
            </div>
            
            <br>
            <a href="index.php" style="color: white; font-weight: bold; text-decoration: none;">Cerrar Sesión</a>
            
            <!-- ====== SECCIÓN DE COPYRIGHT Y CREADOR ====== -->
            <!-- LÍNEA IMPORTANTE: Esta sección muestra la información del creador y copyright -->
            <div class="copyright-footer">
                <!-- LÍNEA IMPORTANTE: Muestra el año actual con PHP date('Y') -->
                <!-- Esto inserta automáticamente el año en el que se visualiza la página -->
                <p>
                    © <?php echo date('Y'); ?> - Todos los derechos reservados
                </p>
                
                <!-- LÍNEA IMPORTANTE: Nombre del creador con clase CSS especial -->
                <!-- La clase 'creator-name' aplica color cian y sombra -->
                <p class="creator-name">
                    Creador: Alexander Monsalve
                </p>
                
                <!-- LÍNEA IMPORTANTE: Institución educativa -->
                <!-- Muestra claramente dónde se realizó el proyecto -->
                <p>
                    Proyecto realizado en: <strong>Universidad de Envigado</strong>
                </p>
            </div>
        </div>
    </div>
<?php endif; ?>
 
<script src="script.js"></script>

<!-- 
    ================================================================================
    RESUMEN DETALLADO DE TODOS LOS CAMBIOS REALIZADOS EN ESTA SESIÓN
    ================================================================================
    
    NOTA PARA MÍ MISMO: Lee esto antes de grabar el video. Estos son todos los cambios
    que hicimos en esta sesión de programación. Explícalo con calma y naturalidad.
    
    ================================================================================
    CAMBIO #1: MEJORA DEL SLIDER CON EMOJIS Y TEXTO DESCRIPTIVO
    ================================================================================
    
    ARCHIVO MODIFICADO: script.js - Función updateValue()
    
    ¿QUÉ HICIMOS?
    Modificamos la función que controla el slider de volumen. Originalmente solo mostraba 
    un número (100). Ahora muestra el número, un emoji y un texto que describe el nivel.
    
    EJEMPLO:
    - Slider en 25 → Muestra: "25 - 🔇 Bajo"
    - Slider en 50 → Muestra: "50 - 🔉 Medio"
    - Slider en 85 → Muestra: "85 - 🔊 Alto"
    
    ¿CÓMO FUNCIONA?
    1. Usamos condicionales if/else para verificar el valor del slider
    2. Si es menor a 33 = "Bajo" con emoji 🔇
    3. Si está entre 33 y 66 = "Medio" con emoji 🔉
    4. Si es mayor a 66 = "Alto" con emoji 🔊
    5. Usamos concatenación con + para juntar: número + "-" + emoji + nivel
    
    LÍNEA CLAVE:
    valueSpan.innerText = val + " - " + emoji + " " + nivel;
    Esto une varios strings (textos) en uno solo.
    
    ================================================================================
    CAMBIO #2: MEJORA VISUAL DE LOS BOTONES
    ================================================================================
    
    ARCHIVO MODIFICADO: styles.css - Regla button y button:hover
    
    ¿QUÉ HICIMOS?
    Los botones ahora se ven más profesionales y modernos. Agregamos:
    - Sombra suave alrededor del botón
    - Efecto de crecimiento cuando pasas el ratón (scale 1.05)
    - Texto más grande
    - Espaciado entre letras mejorado
    
    LÍNEAS CLAVE:
    box-shadow: 0 4px 12px rgba(0, 201, 255, 0.4);  ← Crea sombra alrededor
    transform: scale(1.05);  ← El botón crece un 5% al pasar el ratón
    
    RESULTADO:
    Los botones se ven con más "profundidad" y tienen interactividad visual suave.
    
    ================================================================================
    CAMBIO #3: MEJORA DE INPUTS Y SELECTS (FORMULARIO MODERNO)
    ================================================================================
    
    ARCHIVO MODIFICADO: styles.css - Nuevas reglas para input[type="text"] y select
    
    ¿QUÉ HICIMOS?
    Hicimos que los inputs de texto y los desplegables (select) se vean elegantes:
    
    PARA LOS INPUTS DE TEXTO:
    - Fondo blanco semitransparente
    - Bordes redondeados con transiciones suaves
    - Sombra que cambia al pasar el ratón (efecto hover)
    - Borde color cian cuando haces click (efecto focus)
    - Brillo (glow) azul alrededor cuando está activo
    
    PARA LOS SELECT (DROPDOWNS):
    - Mismos estilos que los inputs (consistencia visual)
    - Un icono de flecha cian personalizado (hecho con SVG)
    - Efectos hover y focus iguales a los inputs
    - Se ve profesional sin la apariencia predeterminada del navegador
    
    LÍNEAS CLAVE:
    input[type="text"]:focus {
        box-shadow: 0 0 0 3px rgba(0, 201, 255, 0.2);  ← "Glow" azul
    }
    
    background-image: url("data:image/svg+xml;...");  ← Icono SVG para select
    
    ================================================================================
    CAMBIO #4: IFRAME DE YOUTUBE EMBEBIDO
    ================================================================================
    
    ARCHIVO MODIFICADO: index.php - Agregado en la sección dashboard
    
    ¿QUÉ HICIMOS?
    Insertamos un video de YouTube que se reproduce automáticamente sin controles.
    El video aparece encima del título "Bienvenido".
    
    CÓMO INCLUIR VIDEOS DE YOUTUBE:
    1. Tomamos la URL del video: https://www.youtube.com/watch?v=Fzcc_R1lQ_Y
    2. Extraemos el ID: Fzcc_R1lQ_Y (la parte después de v=)
    3. Usamos la URL de embed: https://www.youtube.com/embed/Fzcc_R1lQ_Y
    4. Agregamos parámetros:
       - autoplay=1 → Se reproduce solo
       - controls=0 → Sin controles visibles
    5. Metemos todo en un iframe con width="100%" y height="400"
    
    LÍNEA CLAVE:
    src="https://www.youtube.com/embed/Fzcc_R1lQ_Y?autoplay=1&controls=0"
    Los ? y & son para agregar parámetros a la URL.
    
    ================================================================================
    CAMBIO #5: NUEVO FORMULARIO DE "REGISTRO DE UBICACIÓN"
    ================================================================================
    
    ARCHIVOS MODIFICADOS: 
    - index.php: Formulario HTML
    - guardar_viaje.php: Procesa los datos (ARCHIVO NUEVO)
    
    ¿QUÉ HICIMOS?
    Creamos un formulario completo para que los usuarios registren sus viajes con:
    1. Un campo de texto para el username
    2. Un desplegable (select) de PAÍSES (se llena desde la BD)
    3. Un desplegable (select) de CIUDADES (se llena desde la BD)
    4. Un botón que envía los datos
    
    EN index.php - EL FORMULARIO:
    
    El select de países hace una consulta PHP:
    ```php
    $query_paises = "SELECT id, nombre FROM paises ORDER BY nombre";
    $resultado_paises = mysqli_query($conexion, $query_paises);
    
    while($pais = mysqli_fetch_assoc($resultado_paises)) {
        echo "<option value='" . $pais['id'] . "'>" . $pais['nombre'] . "</option>";
    }
    ```
    
    Esto significa:
    1. SELECT id, nombre FROM paises → Pide los países de la BD
    2. mysqli_query() → Ejecuta la consulta
    3. while() → Recorre cada país encontrado
    4. echo "<option>..." → Crea un <option> para cada país
    
    Lo mismo sucede con el select de ciudades.
    
    EN guardar_viaje.php - PROCESAR LOS DATOS:
    
    Este archivo recibe los datos del formulario y los guarda en la BD:
    
    ```php
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $pais = $_POST['pais'];
        $ciudad = $_POST['ciudad'];
        
        $query = "INSERT INTO registros_viajes (username, id_pais, id_ciudad, fecha) 
                  VALUES ('$username', '$pais', '$ciudad', NOW())";
        
        $resultado = mysqli_query($conexion, $query);
    }
    ```
    
    ¿QUÉ HACE?
    1. REQUEST_METHOD == 'POST' → Verifica que el formulario fue enviado
    2. $_POST['username'] → Captura el valor del username
    3. INSERT INTO → Inserta un nuevo registro en la tabla
    4. VALUES () → Los valores que va a insertar
    5. NOW() → Inserta la fecha y hora actual automáticamente
    
    IMPORTANTE: Los nombres de columnas deben coincidir con tu BD:
    - id_pais (no pais_id)
    - id_ciudad (no ciudad_id)
    - fecha (no fecha_registro)
    
    ================================================================================
    CAMBIO #6: ESTILOS MODERNOS PARA EL FORMULARIO
    ================================================================================
    
    ARCHIVO MODIFICADO: styles.css - Nuevas reglas para .section
    
    ¿QUÉ HICIMOS?
    La sección del formulario ahora tiene:
    - Un borde sutil en color cian (rgba(0, 201, 255, 0.2))
    - Una sombra interna (box-shadow inset)
    - Bordes más redondeados (border-radius: 12px)
    - El título del formulario (h3) en color cian con sombra de texto
    
    RESULTADO: El formulario se ve como una sección importante con diseño moderno.
    
    ================================================================================
    CAMBIO #7: SECCIÓN DE COPYRIGHT Y CREADOR
    ================================================================================
    
    ARCHIVO MODIFICADO: index.php - Agregado al final antes de cerrar Sesión
    
    ¿QUÉ HICIMOS?
    Agregamos una pequeña sección que muestra:
    - © 2026 - Todos los derechos reservados
    - Creador: Alexander Monsalve (en color cian)
    - Proyecto realizado en: Universidad de Envigado
    
    LÍNEA CLAVE:
    <?php echo date('Y'); ?>
    
    Esto inserta automáticamente el año actual. Si accedes en 2027, dice 2027.
    Si accedes en 2030, dice 2030. Siempre se actualiza solo.
    
    EN styles.css:
    Creamos dos clases:
    - .copyright-footer → Estilos del contenedor
    - .creator-name → Estilos especiales para el nombre (color cian)
    
    ================================================================================
    RESUMEN RÁPIDO DE ARCHIVOS MODIFICADOS/CREADOS
    ================================================================================
    
    ✅ index.php
       - Agregue require_once('db.php') al inicio
       - Agregue iframe de YouTube
       - Agregue formulario "Registro de Ubicación" con selects dinámicos
       - Agregue sección de copyright y creador
    
    ✅ script.js
       - Modifiqué la función updateValue() para mostrar emojis y niveles
    
    ✅ styles.css
       - Mejoré estilos de botones (sombras, scale en hover)
       - Mejoré estilos de inputs (bordes, sombras, focus effects)
       - Personalicé los select (icono, colores modernos)
       - Agregue estilos para .copyright-footer
    
    ✅ guardar_viaje.php (ARCHIVO NUEVO)
       - Procesa el formulario del registro de ubicación
       - Inserta los datos en la BD
       - Redirige y muestra mensajes de éxito/error
    
    ================================================================================
    CONCEPTOS IMPORTANTES PARA MEMORIZAR
    ================================================================================
    
    1. CONCATENACIÓN DE STRINGS:
       "valor1" + "valor2" → une dos textos en uno
       Ejemplo: "Hola" + " " + "Mundo" → "Hola Mundo"
    
    2. CONDICIONALES (if/else):
       if (condición) { hacer algo }
       else if (otra condición) { hacer otra cosa }
       else { si nada se cumple }
    
    3. CONSULTAS A LA BD:
       SELECT → trae datos de la BD
       INSERT → agrega nuevos datos
       WHERE → filtra resultados
    
    4. LOOPS (mientras):
       while (condición) { hacer algo repetidas veces }
       Sirve para recorrer resultados de una BD
    
    5. $_POST:
       Array especial que recibe datos del formulario
       $_POST['nombre_campo'] → accedes a los valores
    
    6. CSS BOX SHADOW:
       box-shadow: offset-x offset-y blur color;
       Crea sombras alrededor o dentro de elementos
    
    7. CSS TRANSFORM:
       transform: scale(1.05); → Agranda un elemento
       transform: translateX(20px); → Mueve hacia la derecha
    
    ================================================================================
    NOTAS PERSONALES PARA EL VIDEO
    ================================================================================
    
    - Empieza explicando que hicimos cambios en diseño (CSS), funcionalidad (JS)
      y base de datos (PHP)
    - Muestra cada cambio en vivo en el navegador
    - Explica por qué cada cambio hace mejor la aplicación
    - Habla lentamente, eres principiante y otros también lo son
    - Abre el inspector del navegador (F12) y muestra el código que se ve
    - Explica el flujo: usuario llena formulario → guardar_viaje.php recibe datos
      → se guardan en BD → se muestra mensaje
    
    ================================================================================
-->

</body>
</html>