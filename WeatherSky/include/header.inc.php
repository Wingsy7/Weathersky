<?php
declare(strict_types = 1);

// Constantes
define("DEFAULT_TD_NUMBER", "TD 5");
define("DEFAULT_TD_SELECTED", 5);
define("MAX_TD_NUMBER", 9);
define("FIRST_EXERCISE", 1);
define('DEFAULT_THEME', 'jour');

/**
 * Génère l'en-tête HTML avec le bon thème CSS.
 */
function en_tete(string $title = "TD - PHP", bool $close_head = true): string {
    $style = getStyle();
    return "
<!DOCTYPE html>
<html lang=\"fr\">
<head>
    <meta charset=\"UTF-8\" />
    <meta name=\"author\" content=\"Cozma\" />
    <meta name=\"description\" content=\"TD sur le PHP\" />
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\" />
    <title>$title</title>
    <link rel=\"icon\" type=\"image/x-icon\" href=\"pictures/favicon.png\" />
    <link rel=\"stylesheet\" href=\"$style\" />
</head>
<body>
    <header>
        " . TD_actuel_selectionné(DEFAULT_TD_SELECTED) . "
        " . afficherMenu(getNomPageActive()) . "
    </header>";
}

function getNomPageActive(): string {
    return basename($_SERVER["PHP_SELF"], ".php");
}

/**
 * Affiche la navigation principale avec le bouton de bascule de thème.
 */
function TD_actuel_selectionné(int $tab_number_selected = DEFAULT_TD_SELECTED): string {
    $theme = getCurrentTheme();
    $themeTexte = ($theme === 'jour') ? 'Mode Nuit 🌙' : 'Mode Jour ☀️';
    $themeLien = ($theme === 'jour') ? 'nuit' : 'jour';

    $s = "
<nav id=\"header-nav\">
    <ul class=\"header-ul\">";
    
 
    
    // Bouton de bascule de thème
    $s .= "\n<li class=\"header-li\">
            <a href=\"?theme=$themeLien\">$themeTexte</a>
        </li>
    </ul>
</nav>";

    return $s;
}


/**
 * Retourne le thème actuel (cookie ou GET) ou le défaut.
 */
function getCurrentTheme(): string {
    $theme = $_GET['theme'] ?? $_COOKIE['theme'] ?? DEFAULT_THEME;
    return in_array($theme, ['jour', 'nuit']) ? $theme : DEFAULT_THEME;
}

/**
 * Retourne le chemin du fichier CSS approprié.
 */
function getStyle(): string {
    $theme = getCurrentTheme();
    setcookie('theme', $theme, time() + 30 * 24 * 3600, '/'); // Cookie valable 30 jours
    return ($theme === 'nuit') ? "css/dark-style.css" : "css/styles.css";
}

/**
 * Génère la navigation latérale des exercices.
 */
function nav_exercice(int $ex_num = FIRST_EXERCISE): string {
    $s = "
<nav id=\"side-nav\">
    <ul class=\"side-ul\">
        <li class=\"side-h2\">Sommaire</li>";
    
    for ($cur_ex = 1; $cur_ex <= $ex_num; $cur_ex++) {
        $s .= "\n<li class=\"side-li\"><a href=\"#exercice-$cur_ex\">Exercice $cur_ex</a></li>";
    }
    
    $s .= "
    </ul>
</nav>";
    return $s;
}
?>
























