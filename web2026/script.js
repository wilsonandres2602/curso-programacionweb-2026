document.addEventListener("DOMContentLoaded", function () {
    console.log("Proyecto cargado correctamente ✅");

    // ===== SLIDER DE VOLUMEN → cambia opacidad de la Card principal =====
    const slider = document.getElementById("volumen");
    const valorTexto = document.getElementById("volumen-valor");
    const card = document.querySelector(".card");

    if (slider && card) {
        // Función que aplica el cambio
        function actualizarOpacidad() {
            // slider va de 0 a 100 → opacidad de 0.1 a 1.0
            const opacidad = 0.1 + (slider.value / 100) * 0.9;
            card.style.opacity = opacidad.toFixed(2);
            if (valorTexto) {
                valorTexto.textContent = slider.value + "%  (opacidad: " + opacidad.toFixed(2) + ")";
            }
        }

        slider.addEventListener("input", actualizarOpacidad);
        actualizarOpacidad(); // aplicar valor inicial al cargar
    }
});
