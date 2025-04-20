<?php
// Activation du mode strict pour une meilleure vérification des types
declare(strict_types=1);

// Définition du thème par défaut (mode jour)
define('DEFAULT_THEME', 'jour');

// =========================================
// 🔼 FONCTION : Génère l'en-tête HTML
// =========================================

/**
 * Génère l'en-tête HTML complet (avec balises <html>, <head>, et <body>).
 * Intègre la feuille de style du thème sélectionné et la navigation principale.
 *
 * @param string $title Titre de la page
 * @param bool $close_head Permet de fermer la balise <head> automatiquement (non utilisé ici mais prévu)
 * @return string Code HTML de l'en-tête
 */
function en_tete(string $title = "WeatherSky", bool $close_head = true): string {
    $style = getStyle(); // Récupère le bon style en fonction du thème (jour/nuit)
    error_log("Style chargé : $style"); // Journalisation (utile pour debug)

    // Bloc HTML de l'en-tête
    $html = "
    <!DOCTYPE html>
    <html lang=\"fr\">
    <head>
        <meta charset=\"UTF-8\" />
        <meta name=\"author\" content=\"Cozma\" />
        <meta name=\"description\" content=\"Projet météo\" />
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\" />
        <title>$title</title>
        <link rel=\"icon\" type=\"image/png\" href=\"logo/logo.png?v=" . time() . "\" />
        <link id=\"theme\" rel=\"stylesheet\" href=\"$style?v=" . time() . "\" />
        <link href=\"https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined\" rel=\"stylesheet\" />
        <style>
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 1,
                'wght' 400,
                'GRAD' 0,
                'opsz' 48;
            vertical-align: middle;
            font-size: 1.5em;
        }
        </style>
    </head>
    <body>
        <header>
            " . navigation() . "
        </header>";

    error_log("En-tête généré");
    return $html;
}

// =========================================
// 🧭 FONCTION : Génère la barre de navigation
// =========================================

/**
 * Génère le menu de navigation avec mise en évidence de la page active.
 *
 * @return string Code HTML du menu de navigation
 */
function navigation(): string {
    $theme = getCurrentTheme(); // Récupère le thème actuel (jour ou nuit)
    $themeTexte = ($theme === 'jour') ? 'Mode Nuit 🌙' : 'Mode Jour ☀️';
    $themeLien = ($theme === 'jour') ? 'nuit' : 'jour';

    $currentPage = basename($_SERVER['PHP_SELF']); // Nom du fichier courant (ex: index.php)
    error_log("Page actuelle : $currentPage");

    // Construction du menu avec la classe "current-page" sur l'élément actif
    $s = '
    <nav id="header-nav">
        <a href="index.php" class="logo-link">
            <img src="/logo/logo.png" alt="Logo WeatherSky" class="site-logo" />
        </a>
        <ul class="header-ul">
            <li class="header-li' . ($currentPage === 'index.php' ? ' current-page' : '') . '">
                <a href="index.php">Accueil</a>
            </li>
            <li class="header-li' . ($currentPage === 'statistique.php' ? ' current-page' : '') . '">
                <a href="statistique.php">Statistique</a>
            </li>
            <li class="header-li' . ($currentPage === 'map.php' ? ' current-page' : '') . '">
                <a href="map.php">Carte</a>
            </li>
            <li class="header-li' . ($currentPage === 'meteo2.php' ? ' current-page' : '') . '">
                <a href="meteo2.php">Météo spécifique</a>
            </li>
            <li class="header-li">
                <a href="?theme=' . $themeLien . '">' . $themeTexte . '</a>
            </li>
        </ul>
    </nav>';

    return $s;
}

// =========================================
// 🎨 FONCTION : Détermine le thème actuel
// =========================================

/**
 * Récupère le thème choisi par l'utilisateur (via paramètre GET ou cookie).
 *
 * @return string 'jour' ou 'nuit'
 */
function getCurrentTheme(): string {
    // Priorité au paramètre GET, sinon cookie, sinon thème par défaut
    $theme = $_GET['theme'] ?? $_COOKIE['theme'] ?? DEFAULT_THEME;
    error_log("Thème sélectionné : $theme");

    // Vérifie que le thème est bien valide
    return in_array($theme, ['jour', 'nuit']) ? $theme : DEFAULT_THEME;
}

// =========================================
// 🧾 FONCTION : Sélectionne la feuille de style du thème
// =========================================

/**
 * Retourne le chemin de la feuille de style correspondant au thème actuel,
 * et définit un cookie pour conserver le choix pendant 30 jours.
 *
 * @return string Chemin CSS
 */
function getStyle(): string {
    $theme = getCurrentTheme();
    // Définit le cookie "theme" pour une durée de 30 jours
    setcookie('theme', $theme, time() + 30 * 24 * 3600, '/');

    // Sélection du fichier CSS selon le thème
    $style = ($theme === 'nuit') ? "css/dark-style.css" : "css/style.css";
    error_log("Style sélectionné : $style");

    return $style;
}
?>
