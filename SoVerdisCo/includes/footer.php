<?php // includes/footer.php ?>
<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <svg width="24" height="24" viewBox="0 0 28 28" fill="none" aria-hidden="true">
                <circle cx="14" cy="14" r="7" fill="#F5A623"/>
                <g stroke="#F5A623" stroke-width="1.8" stroke-linecap="round">
                    <line x1="14" y1="1" x2="14" y2="4"/><line x1="14" y1="24" x2="14" y2="27"/>
                    <line x1="1" y1="14" x2="4" y2="14"/><line x1="24" y1="14" x2="27" y2="14"/>
                    <line x1="4.93" y1="4.93" x2="7.05" y2="7.05"/><line x1="20.95" y1="20.95" x2="23.07" y2="23.07"/>
                    <line x1="23.07" y1="4.93" x2="20.95" y2="7.05"/><line x1="7.05" y1="20.95" x2="4.93" y2="23.07"/>
                </g>
            </svg>
            <span class="footer-name">SoVerdisCo</span>
            <p class="footer-tagline">Energía solar para una Colombia sostenible</p>
        </div>
        <nav class="footer-links" aria-label="Enlaces secundarios">
            <div class="footer-col">
                <h3>Secciones</h3>
                <ul>
                    <li><a href="<?= $root ?? '' ?>index.php">Inicio</a></li>
                    <li><a href="<?= $root ?? '' ?>energia-solar.php">Energía Solar</a></li>
                    <li><a href="<?= $root ?? '' ?>panorama.php">Panorama Colombia</a></li>
                    <li><a href="<?= $root ?? '' ?>calculadora.php">Calculadora</a></li>
                    <li><a href="<?= $root ?? '' ?>noticias.php">Noticias</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Referencias</h3>
                <ul>
                    <li><a href="https://www.upme.gov.co" target="_blank" rel="noopener">UPME</a></li>
                    <li><a href="https://www.ideam.gov.co" target="_blank" rel="noopener">IDEAM</a></li>
                    <li><a href="https://www.ser-colombia.org" target="_blank" rel="noopener">SER Colombia</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Administración</h3>
                <ul>
                    <li><a href="<?= $root ?? '' ?>admin/login.php">🔒 Acceso Admin</a></li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> SoVerdisCo — Proyecto académico. Datos: UPME, IDEAM, SER Colombia.</p>
    </div>
</footer>

<script src="<?= $root ?? '' ?>assets/js/main.js"></script>
</body>
</html>