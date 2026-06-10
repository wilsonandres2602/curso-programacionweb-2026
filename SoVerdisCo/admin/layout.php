<?php
// admin/layout.php
// Asegurar que las variables globales existan
global $adminName, $adminRol, $adminCurrent;
if (!isset($adminName)) $adminName = 'Administrador';
if (!isset($adminRol)) $adminRol = 'editor';
if (!isset($adminCurrent)) $adminCurrent = basename($_SERVER['PHP_SELF'], '.php');

function adminHeader($title) {
    global $adminName, $adminCurrent;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> — Admin SoVerdisCo</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="admin-body">
    <aside class="admin-sidebar">
        <div class="admin-sidebar-brand">
            <svg width="24" height="24" viewBox="0 0 28 28" fill="none">
                <circle cx="14" cy="14" r="7" fill="#F5A623"/>
                <g stroke="#F5A623" stroke-width="1.8" stroke-linecap="round">
                    <line x1="14" y1="1" x2="14" y2="4"/><line x1="14" y1="24" x2="14" y2="27"/>
                    <line x1="1" y1="14" x2="4" y2="14"/><line x1="24" y1="14" x2="27" y2="14"/>
                    <line x1="4.93" y1="4.93" x2="7.05" y2="7.05"/><line x1="20.95" y1="20.95" x2="23.07" y2="23.07"/>
                    <line x1="23.07" y1="4.93" x2="20.95" y2="7.05"/><line x1="7.05" y1="20.95" x2="4.93" y2="23.07"/>
                </g>
            </svg>
            <div>SoVerdisCo<span>Panel de administración</span></div>
        </div>
        <nav class="admin-nav">
            <?php
            $links = [
                ['dashboard',   '📊', 'Dashboard'],
                ['articulos',   '📝', 'Artículos'],
                ['crear',       '➕', 'Nuevo artículo'],
                ['proyectos',   '🏭', 'Proyectos solares'],
                ['paginas',     '📄', 'Páginas estáticas'],
                ['usuarios',    '👤', 'Usuarios'],
            ];
            foreach ($links as [$page, $icon, $label]):
                $active = ($page === $adminCurrent) ? 'active' : '';
            ?>
            <a href="<?= $page ?>.php" class="admin-nav-link <?= $active ?>">
                <span class="nav-icon"><?= $icon ?></span><?= $label ?>
            </a>
            <?php endforeach; ?>
            <a href="../index.php" class="admin-nav-link" target="_blank" rel="noopener">
                <span class="nav-icon">🌐</span>Ver sitio público
            </a>
        </nav>
        <div class="admin-logout">
            <a href="logout.php">🚪 Cerrar sesión</a>
        </div>
    </aside>
    <main class="admin-main">
        <div class="admin-topbar">
            <h1><?= htmlspecialchars($title) ?></h1>
            <div><span>👤 <?= htmlspecialchars($adminName) ?></span></div>
        </div>
<?php
}

function adminFooter() {
?>
    </main>
</div>
<script src="../assets/js/main.js"></script>
</body>
</html>
<?php
}