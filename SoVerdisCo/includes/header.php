<?php
// includes/header.php
$current = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'SoVerdisCo') ?> — SoVerdisCo</title>
    <link rel="stylesheet" href="<?= $root ?? '' ?>assets/css/style.css">
    <link rel="icon" href="<?= $root ?? '' ?>assets/img/favicon.svg" type="image/svg+xml">
</head>
<body>

<header class="site-header">
    <nav class="navbar" role="navigation" aria-label="Navegación principal">
        <a href="<?= $root ?? '' ?>index.php" class="nav-brand" aria-label="SoVerdisCo inicio">
            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" aria-hidden="true">
                <circle cx="14" cy="14" r="7" fill="#F5A623"/>
                <g stroke="#F5A623" stroke-width="1.8" stroke-linecap="round">
                    <line x1="14" y1="1" x2="14" y2="4"/>
                    <line x1="14" y1="24" x2="14" y2="27"/>
                    <line x1="1" y1="14" x2="4" y2="14"/>
                    <line x1="24" y1="14" x2="27" y2="14"/>
                    <line x1="4.93" y1="4.93" x2="7.05" y2="7.05"/>
                    <line x1="20.95" y1="20.95" x2="23.07" y2="23.07"/>
                    <line x1="23.07" y1="4.93" x2="20.95" y2="7.05"/>
                    <line x1="7.05" y1="20.95" x2="4.93" y2="23.07"/>
                </g>
            </svg>
            <span>SoVerdisCo</span>
        </a>

        <button class="nav-toggle" aria-label="Abrir menú" aria-expanded="false" aria-controls="nav-menu">
            <span></span><span></span><span></span>
        </button>

        <ul class="nav-menu" id="nav-menu" role="list">
            <li><a href="<?= $root ?? '' ?>index.php"         class="nav-link <?= $current==='index'?'active':'' ?>">Inicio</a></li>
            <li><a href="<?= $root ?? '' ?>energia-solar.php" class="nav-link <?= $current==='energia-solar'?'active':'' ?>">Energía Solar</a></li>
            <li><a href="<?= $root ?? '' ?>panorama.php"      class="nav-link <?= $current==='panorama'?'active':'' ?>">Panorama</a></li>
            <li><a href="<?= $root ?? '' ?>calculadora.php"   class="nav-link <?= $current==='calculadora'?'active':'' ?>">Calculadora</a></li>
            <li><a href="<?= $root ?? '' ?>noticias.php"      class="nav-link <?= $current==='noticias'?'active':'' ?>">Noticias</a></li>
        </ul>
    </nav>
</header>