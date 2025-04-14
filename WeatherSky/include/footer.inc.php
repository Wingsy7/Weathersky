<?php
require_once "./include/util.inc.php"; // Inclure le fichier util.php
// Appeler la fonction get_navigateur() pour récupérer les informations du navigateur
$browser_info = get_browser_info();
?>

<footer>
    <p class="copyright">Cozma Miroslav, étudiant à <a href="https://www.cyu.fr/">Cergy Université</a>.</p>
    <p>Dernière mise à jour:  <time datetime="2025-03-10">11/03/2025</time></p>
    <p>L2-Informatique</p>
    <a href="index.php"><p>Accueil</p></a>
    <a href="sitemap.php"><p>Plan du Site</p></a>
    <p id="browser-info"> Navigateur actuel: <?php echo get_browser_info(); ?></p>
    <a href= "tech.php"><p>Page Tech du projet de Dev Web</p></a>
</footer>
</body>
</html>