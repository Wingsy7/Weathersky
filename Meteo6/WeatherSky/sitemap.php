<?php
/* Requires and includes */
require "./include/header.inc.php";
?>

<?php echo en_tete("Plan du site", false);
$style_css = getStyle(); ?>

<main>
    <div class="main-part">
        <h1 class="h1-custom">Plan du site</h1>

        <section>
            <ul>
                <li>
                    <h3>🏠 Accueil</h3>
                    <ul>
                        <li><a href="index.php">Page d'accueil</a></li>
                    </ul>
                </li>

                <li>
                    <h3>🗺️ Carte météo</h3>
                    <ul>
                        <li><a href="map.php">Voir la carte interactive</a></li>
                    </ul>
                </li>

                <li>
                    <h3>🌤️ Prévisions spécifique</h3>
                    <ul>
                        <li><a href="meteo2.php">Voir la météo d'une ville</a></li>
                    </ul>
                </li>

                <li>
                    <h3>📊 Statistiques</h3>
                    <ul>
                        <li><a href="statistique.php">Données météo</a></li>
                    </ul>
                </li>

                <li>
                    <h3>🔧 Projet technique</h3>
                    <ul>
                        <li><a href="./projet/tech.php">Page technique du projet</a></li>
                    </ul>
                </li>
            </ul>
        </section>
    </div>
</main>

<?php 
require "./include/footer.inc.php";
?>
