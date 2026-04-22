function cambiarColor() {
    document.body.style.background = "linear-gradient(135deg, #ff6a00, #ee0979)";
}
 
function mostrarAlerta() {
    alert("¡Hola! Esta es una función de JavaScript.");
}
 
function addTask() {
    const input = document.getElementById("taskInput");
    if(input.value.trim() !== "") {
        const li = document.createElement("li");
        li.textContent = input.value;
        li.style.textAlign = "left";
        document.getElementById("taskList").appendChild(li);
        input.value = "";
    }
}
 
/* 
    ================================================================================
    FUNCIÓN MEJORADA: updateValue() - SLIDER CON EMOJIS Y NIVELES
    ================================================================================
    
    EXPLICACIÓN SIMPLE:
    Esta función se ejecuta cada vez que el usuario mueve el slider de volumen.
    Antes: Solo mostraba el número (100)
    Ahora: Muestra número + emoji + nivel (100 - 🔊 Alto)
    
    ¿CÓMO FUNCIONA PASO A PASO?
    
    1. Le llega el valor del slider (0-100) en la variable "val"
    
    2. Captura el elemento HTML donde debe mostrar el resultado:
       const valueSpan = document.getElementById("value");
       
    3. Crea dos variables vacías:
       let nivel;   → aquí guardará "Bajo", "Medio" o "Alto"
       let emoji;   → aquí guardará 🔇, 🔉 o 🔊
    
    4. Usa if/else para determinar qué texto y emoji mostrar:
       - Si val < 33 → Bajo (🔇)
       - Si val < 66 → Medio (🔉)
       - Si val >= 66 → Alto (🔊)
    
    5. Finalmente, une todo en una sola línea y lo muestra:
       valueSpan.innerText = val + " - " + emoji + " " + nivel;
       
    EJEMPLO EN TIEMPO REAL:
    - Usuario mueve slider a 25
    - val = 25
    - 25 < 33 → nivel = "Bajo", emoji = "🔇"
    - Muestra en pantalla: "25 - 🔇 Bajo"
    
    CONCEPTO A MEMORIZAR:
    La concatenación (+) en JavaScript une strings:
    "texto1" + "texto2" = "texto1texto2"
    "50" + " - " + "🔊" + " Alto" = "50 - 🔊 Alto"
*/

function updateValue(val) {
    // Obtener el elemento span donde mostraremos la información
    const valueSpan = document.getElementById("value");
    
    // Crear variables para guardar el nivel y emoji
    let nivel;
    let emoji;
    
    // Si el valor es menor a 33 se considera bajo
    if (val < 33) {
        nivel = "Bajo";
        emoji = "🔇"; // Parlante bajo
    }
    // Si está entre 33 y 66 es medio
    else if (val < 66) {
        nivel = "Medio";
        emoji = "🔉"; // Parlante medio
    }
    // Si es mayor a 66 es alto
    else {
        nivel = "Alto";
        emoji = "🔊"; // Parlante alto
    }
    
    // LÍNEA IMPORTANTE: Mostrar el valor numérico, el emoji y el nivel en texto
    // Ejemplo: "75 - 🔊 Alto"
    valueSpan.innerText = val + " - " + emoji + " " + nivel;
}
 
function toggleModo(el) {
    document.body.style.background = el.checked ? "#111" : "linear-gradient(135deg, #667eea, #764ba2)";
}

/*
    ================================================================================
    NOTAS PARA ENTENDER JAVASCRIPT MEJOR
    ================================================================================
    
    1. SELECCIONAR ELEMENTOS DEL HTML:
       document.getElementById("id") → Busca por ID
       document.querySelector(".clase") → Busca por clase
    
    2. MODIFICAR CONTENIDO:
       element.innerText = "nuevo texto" → Cambia el texto
       element.style.color = "red" → Cambia el CSS
    
    3. ESCUCHAR EVENTOS:
       oninput="updateValue(this.value)" → Se ejecuta mientras mueves
       onclick="funccion()" → Se ejecuta al hacer click
       onchange="funccion()" → Se ejecuta al cambiar valor
    
    4. PASAR PARÁMETROS:
       this.value → Envía el valor del elemento actual
       updateValue(25) → Envía el número 25 a la función
    
    5. VARIABLES:
       const → Variable que NO puede cambiar (constante)
       let → Variable que SÍ puede cambiar
*/
