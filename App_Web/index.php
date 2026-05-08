<?php
include("db.php");

// Obtener países
$paises = [];
$result = mysqli_query($conexion, "SELECT * FROM paises");
while($row = mysqli_fetch_assoc($result)){
  $paises[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>App Web</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- BANNER -->
<iframe width="100%" height="200"
src="https://www.youtube.com/embed/dQw4w9WgXcQ">
</iframe>

<div class="container">
<div class="card">

<h1>Bienvenido 👋</h1>

<!-- BOTONES -->
<div class="section">
<button onclick="cambiarColor()">Cambiar Color</button>
<button onclick="mostrarAlerta()">Alerta</button>
</div>

<!-- TAREAS -->
<div class="section">
<input type="text" id="taskInput">
<button onclick="addTask()">Agregar</button>
<ul id="taskList"></ul>
</div>

<!-- SLIDER -->
<div class="section">
<label>Volumen:</label>
<input type="range" min="0" max="100" value="100" oninput="updateValue(this.value)">
<span id="value">100</span>
</div>

<!-- SWITCH -->
<div class="section">
<label class="switch">
<input type="checkbox" onchange="toggleModo(this)">
<span class="slider"></span>
</label>
<p>Modo oscuro</p>
</div>

</div>
</div>

<!-- FORMULARIO -->
<div class="container">
<div class="card">

<h2>Registro de Ubicación</h2>

<form id="formUbicacion" onsubmit="guardarDestino(event)">
<input type="text" name="nombre_usuario" placeholder="Usuario">

<select name="id_pais" id="pais" onchange="cargarCiudades(this.value)">
<option value="">Seleccione país</option>

<?php
for($i=0;$i<count($paises);$i++){
  echo "<option value='".$paises[$i]['id']."'>".$paises[$i]['nombre']."</option>";
}
?>

</select>

<select name="id_ciudad" id="ciudad">
<option>Seleccione ciudad</option>
</select>

<button type="submit">Guardar Destino</button>
</form>

<p id="mensajeRegistro"></p>

</div>
</div>

<!-- ALERTA -->
<div id="customAlert" class="alert hidden" onclick="cerrarAlerta(event)">
<div class="alert-box">
<p>Hola 👋 Esto es JavaScript!</p>
<button onclick="cerrarAlertaBoton()">Cerrar</button>
</div>
</div>

<!-- FOOTER -->
<footer class="footer">
<p>Creado por Isaac Posada</p>
</footer>

<script src="script.js"></script>
</body>
</html>