<?php
/*
    ================================================================================
    ARCHIVO: guardar_viaje.php
    PROPÓSITO: Procesar el formulario de "Registro de Ubicación"
    ================================================================================
    
    ¿QUÉ HACE ESTE ARCHIVO?
    
    Cuando el usuario llena el formulario en index.php y hace click en "Guardar Destino",
    se envían los datos AQUÍ. Este archivo:
    
    1. Recibe los datos del formulario (username, país, ciudad)
    2. Verifica que el formulario fue enviado correctamente
    3. Prepara una consulta SQL INSERT
    4. Inserta los datos en la tabla registros_viajes de la BD
    5. Muestra un mensaje de éxito o error
    6. Redirige de vuelta al dashboard
    
    FLUJO DEL PROGRAMA:
    
    Usuario rellena formulario
         ↓
    Click en "Guardar Destino"
         ↓
    El formulario se envía a guardar_viaje.php (por action="guardar_viaje.php")
         ↓
    Este archivo procesa los datos
         ↓
    Se insertan en la BD
         ↓
    Se muestra resultado (éxito o error)
         ↓
    Se redirige a index.php con mensaje
    
    ================================================================================
    EXPLICACIÓN LÍNEA POR LÍNEA
    ================================================================================
*/

// LÍNEA IMPORTANTE: Incluir la conexión a la BD
// Sin esto, no podríamos comunicarnos con la BD
require_once('db.php');

// LÍNEA IMPORTANTE: Verificar si el formulario fue enviado
// $_SERVER['REQUEST_METHOD'] es una variable especial de PHP
// Tiene el valor 'POST' cuando un formulario fue enviado
// Tiene el valor 'GET' cuando accedes directamente a la URL
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    /*
        EXPLICACIÓN DE $_POST:
        
        $_POST es un "contenedor" especial en PHP que guarda los datos enviados
        desde un formulario HTML. Funciona como un diccionario (array):
        
        Los names del HTML se convierten en claves:
        <input name="username"> → $_POST['username']
        <select name="pais"> → $_POST['pais']
        <select name="ciudad"> → $_POST['ciudad']
        
        Los valores que escribió el usuario están en $_POST['clave']
    */
    
    // LÍNEA IMPORTANTE: Capturar los datos del formulario
    // Estos valores vienen desde el HTML de index.php
    $username = $_POST['username'];   // Lo que escribió en el input
    $pais = $_POST['pais'];           // El país que seleccionó
    $ciudad = $_POST['ciudad'];       // La ciudad que seleccionó
    
    /*
        EXPLICACIÓN DE LA CONSULTA SQL:
        
        INSERT INTO - Comando para agregar un nuevo registro a la tabla
        registros_viajes - Nombre de la tabla donde guardaremos
        (username, id_pais, id_ciudad, fecha) - Columnas donde guardaremos datos
        VALUES () - Los valores que vamos a insertar
        NOW() - Función SQL que inserta la fecha y hora actual automáticamente
        
        IMPORTANTE: Los nombres de columnas DEBEN coincidir con tu BD:
        - username ✓
        - id_pais (NO pais_id)
        - id_ciudad (NO ciudad_id)
        - fecha (NO fecha_registro)
    */
    
    // LÍNEA IMPORTANTE: Crear la consulta SQL INSERT
    // Esta consulta va a insertar una fila nueva en la tabla
    $query = "INSERT INTO registros_viajes (username, id_pais, id_ciudad, fecha) 
              VALUES ('$username', '$pais', '$ciudad', NOW())";
    
    // LÍNEA IMPORTANTE: Ejecutar la consulta en la BD
    // mysqli_query() envía la consulta a la BD y devuelve el resultado
    // Si tiene éxito: $resultado = true
    // Si hay error: $resultado = false
    $resultado = mysqli_query($conexion, $query);
    
    /*
        EXPLICACIÓN DE SINCRONIZACIÓN:
        
        La variable $resultado contiene:
        - true → Si se guardó correctamente
        - false → Si hubo un error
        
        Usamos if() para verificar qué pasó
    */
    
    // LÍNEA IMPORTANTE: Verificar si la inserción fue exitosa
    if($resultado) {
        // Si fue exitoso, mostramos un mensaje y redirigimos
        
        // alert() es una ventana emergente en JavaScript
        // ✅ es solo un emoji para que se vea bonito
        echo "<script>
            alert('✅ Destino guardado exitosamente');
            window.location.href = 'index.php?success=1';
        </script>";
        
        /*
            EXPLICACIÓN:
            
            echo "<script>" → Enviamos código JavaScript desde PHP
            alert() → Muestra una ventana emergente
            window.location.href → Redirige a otra página
            'index.php?success=1' → Vuelve al dashboard con ?success=1
                                    Esto hace que se muestre el dashboard
        */
        
    } else {
        // Si hubo error, mostramos el error
        
        echo "<script>
            alert('❌ Error al guardar: " . mysqli_error($conexion) . "');
            window.location.href = 'index.php?success=1';
        </script>";
        
        /*
            EXPLICACIÓN:
            
            mysqli_error($conexion) → Te dice qué error tuvo la BD
            . " " . → El . concatena strings en PHP
            
            Ejemplo de error: "Duplicate entry '1' for key 'id'"
        */
    }
}

/*
    ================================================================================
    CONCEPTOS CLAVE DE PHP PARA MEMORIZAR
    ================================================================================
    
    1. require_once() - Incluye un archivo PHP y lo ejecuta
    2. $_POST['clave'] - Array con datos del formulario
    3. $_SERVER['REQUEST_METHOD'] - Te dice si es GET, POST, etc
    4. mysqli_query($conexion, $query) - Ejecuta una consulta SQL
    5. mysqli_error($conexion) - Te dice qué error tuvo la consulta
    6. echo "" - Imprime texto en pantalla (o código HTML/JS)
    7. Concatenación con . (punto) - Junta strings
    8. NOW() - Función SQL que devuelve la fecha/hora actual
    
    ================================================================================
    ERRORES COMUNES A EVITAR
    ================================================================================
    
    ❌ Nombres de columnas incorrectos
       Usé: id_pais, id_ciudad, fecha
       Tu tabla tiene estos nombres exactamente.
    
    ❌ Olvidar require_once('db.php')
       Sin esto no tienes $conexion y falla todo.
    
    ❌ Usar comillas simples incorrectamente
       "INSERT INTO..." - Debe estar entre comillas dobles
       'valor' - Los valores también pueden ir en comillas simples
    
    ❌ No verificar si el formulario fue enviado
       Siempre usa: if($_SERVER['REQUEST_METHOD'] == 'POST')
    
    ================================================================================
    NOTAS PARA EL VIDEO
    ================================================================================
    
    1. Explica el flujo: formulario → este archivo → BD
    2. Muestra cómo se ven los datos en phpMyAdmin después de guardar
    3. Explica que NOW() es un "ahorro de trabajo" (no necesitas escribir la fecha)
    4. Muestra qué pasa si hay un error en la BD
*/
?>

