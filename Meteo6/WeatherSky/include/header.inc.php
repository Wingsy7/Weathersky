<?php
declare(strict_types=1);

define('DEFAULT_THEME', 'jour');

function en_tete(string $title = "WeatherSky", bool $close_head = true): string {
    $style = getStyle();
    error_log("Style chargé : $style");
    
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

function navigation(): string {
    $theme = getCurrentTheme();
    $themeTexte = ($theme === 'jour') ? 'Mode Nuit 🌙' : 'Mode Jour ☀️';
    $themeLien = ($theme === 'jour') ? 'nuit' : 'jour';

    $currentPage = basename($_SERVER['PHP_SELF']);
    error_log("Page actuelle : $currentPage");

    $s = '
    <nav id="header-nav">
        <a href="index.php" class="logo-link">
            <img src="/logo/logo.png" alt="Logo WeatherSky" class="site-logo">
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

function getCurrentTheme(): string {
    $theme = $_GET['theme'] ?? $_COOKIE['theme'] ?? DEFAULT_THEME;
    error_log("Thème sélectionné : $theme");
    return in_array($theme, ['jour', 'nuit']) ? $theme : DEFAULT_THEME;
}

function getStyle(): string {
    $theme = getCurrentTheme();
    setcookie('theme', $theme, time() + 30 * 24 * 3600, '/');
    $style = ($theme === 'nuit') ? "css/dark-style.css" : "css/style.css";
    error_log("Style sélectionné : $style");
    return $style;
}
?>











