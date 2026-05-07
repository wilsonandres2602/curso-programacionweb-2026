<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Ubicación</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "db.php"; ?>

<div class="registro-wrapper">

    <div class="registro-card">

        <!-- PUNTO 4: Banner con imagen de mundo y avión (sin video) -->
        <div class="banner">
            <span class="banner-emoji">🌍</span>
            <span class="banner-emoji">✈️</span>
            <div class="banner-overlay">
                <h1>Registro de Ubicación</h1>
            </div>
        </div>

        <!-- Mensaje confirmación -->
        <?php if (isset($_GET['guardado'])): ?>
            <div class="msg <?= $_GET['guardado'] === 'ok' ? 'msg-ok' : 'msg-error' ?>">
                <?= $_GET['guardado'] === 'ok' ? '✅ Destino guardado.' : '⚠️ Completa todos los campos.' ?>
            </div>
        <?php endif; ?>

        <!-- PUNTO 3: Formulario -->
        <form action="guardar.php" method="POST" class="registro-form">

            <div class="form-group">
                <label>Nombre de usuario</label>
                <input type="text" name="usuario" placeholder="Ej: juan123" required>
            </div>

            <div class="form-group">
                <label>País</label>
                <select id="pais" name="pais" onchange="cargarCiudades(this.value)" required>
                    <option value="">— Seleccione país —</option>
                    <?php
                    $paises = $conn->query("SELECT * FROM paises ORDER BY nombre");
                    while($row = $paises->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Ciudad</label>
                <select id="ciudad" name="ciudad" required>
                    <option value="">— Seleccione ciudad —</option>
                </select>
            </div>

            <button type="submit" class="btn-guardar">💾 Guardar Destino</button>

        </form>

        <a href="index.php?success=1" class="back-link">← Volver al Dashboard</a>

    </div>

    <!-- PUNTO 4: Footer -->
    <footer class="footer">
        <p>✨ <strong>Mariana Arenas</strong> &nbsp;|&nbsp; Proyecto Web 2025</p>
        <p>
            <a href="marianaarenas2709@gmail.com">📧 Contacto</a>
            &nbsp;&nbsp;
            <a href="#">🔗 LinkedIn</a>
        </p>
    </footer>

</div>

<script src="script.js"></script>
</body>
</html>