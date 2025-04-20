<?php
// Inclusion du header et des fonctions
require "./include/header.inc.php";

// Génération de l'en-tête avec fermeture automatique du </head> (pas besoin de false ici)
echo en_tete("Plan du site");
?>

<main>
    <div class="main-part">
        <h1 class="h1-custom">Plan du site</h1>

        <section>
            <ul>
                <!-- Accueil -->
                <li>
                    <h3>🏠 Accueil</h3>
                    <ul>
                        <li><a href="index.php">Page d'accueil</a></li>
                    </ul>
                </li>

                <!-- Carte météo -->
                <li>
                    <h3>🗺️ Carte météo</h3>
                    <ul>
                        <li><a href="map.php">Voir la carte interactive</a></li>
                    </ul>
                </li>

                <!-- Météo spécifique -->
                <li>
                    <h3>🌤️ Prévisions spécifiques</h3>
                    <ul>
                        <li><a href="meteo2.php">Voir la météo d'une ville</a></li>
                    </ul>
                </li>

                <!-- Statistiques -->
                <li>
                    <h3>📊 Statistiques</h3>
                    <ul>
                        <li><a href="statistique.php">Données météo</a></li>
                    </ul>
                </li>

                <!-- Page technique -->
                <li>
                    <h3>🔧 Projet technique</h3>
                    <ul>
                        <li><a href="tech.php">Page technique du projet</a></li>
                    </ul>
                </li>
            </ul>
        </section>
    </div>
</main>

<?php
// Inclusion du pied de page
require "./include/footer.inc.php";
?>
