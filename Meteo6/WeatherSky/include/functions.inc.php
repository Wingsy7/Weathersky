<?php
// ========================================
// 📅 UTILITAIRES DATES
// ========================================

/**
 * Retourne la date actuelle au format YYYY-MM-DD
 */
function getTodayDate() {
    return date("Y-m-d");
}

// ========================================
// 📷 API NASA APOD
// ========================================

/**
 * Récupère l'image du jour (APOD) via l'API de la NASA
 * Retourne un bloc HTML contenant l'image ou la vidéo
 */
function getAPODImage(): string {
    $api_key = "DEMO_KEY"; // À remplacer par votre propre clé API
    $date = getTodayDate();
    $url = "https://api.nasa.gov/planetary/apod?api_key=$api_key&date=$date";

    // Récupération de la réponse JSON de l’API
    $response = @file_get_contents($url);
    if ($response === false) {
        return '<p>Impossible de récupérer l’image du jour. Veuillez réessayer plus tard.</p>';
    }

    // Décodage des données JSON
    $data = json_decode($response, true);
    if (!$data || !isset($data['media_type']) || !isset($data['url'])) {
        return '<p>Données invalides reçues depuis l’API NASA.</p>';
    }

    // Si le contenu est une vidéo
    if ($data['media_type'] === 'video') {
        return '
        <div class="apod-video">
            <iframe width="560" height="315" src="' . htmlspecialchars($data['url']) . '" frameborder="0" allowfullscreen></iframe>
            <p>' . htmlspecialchars($data['title'] ?? '') . '</p>
            <p>' . htmlspecialchars($data['explanation'] ?? '') . '</p>
        </div>';
    } else {
        // Sinon, afficher l'image
        return '
        <figure>
            <img src="' . htmlspecialchars($data['url']) . '" alt="APOD Image" class="apodNasa">
            <figcaption>' . htmlspecialchars($data['explanation']) . '</figcaption>
        </figure>';
    }
}

// ========================================
// 🌍 GÉOLOCALISATION
// ========================================

/**
 * Détecte l’adresse IP du visiteur
 */
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

/**
 * Récupère la géolocalisation via geoplugin (format XML)
 */
function getVisitorLocationXML() {
    $api_url = 'http://www.geoplugin.net/xml.gp?ip=' . getUserIP();
    $response = @file_get_contents($api_url);
    if ($response === false) return [];

    $data = @simplexml_load_string($response);
    if (!$data) return [];

    return [
        'city' => (string)$data->geoplugin_city,
        'region' => (string)$data->geoplugin_region,
        'country' => (string)$data->geoplugin_countryName,
        'latitude' => (float)$data->geoplugin_latitude,
        'longitude' => (float)$data->geoplugin_longitude
    ];
}

/**
 * Récupère la géolocalisation via ipinfo.io (format JSON)
 */
function getVisitorLocationJSON() {
    $user_ip = getUserIP();
    $api_url = "https://ipinfo.io/$user_ip/json";

    $response = @file_get_contents($api_url);
    if ($response === false) return [];

    $data = json_decode($response, true);
    if (!$data || !isset($data['loc'])) return [];

    [$lat, $lon] = explode(',', $data['loc']);

    return [
        'city' => $data['city'] ?? '',
        'region' => $data['region'] ?? '',
        'country' => $data['country'] ?? '',
        'latitude' => (float)$lat,
        'longitude' => (float)$lon
    ];
}

/**
 * Récupère la géolocalisation via WhatIsMyIP (WIP, format XML)
 */
function getVisitorLocationWIP() {
    $user_ip = getUserIP();
    $api_key = "e964f74aadfe25702230dfcbac03a675";
    $api_url = "https://api.whatismyip.com/ip-address-lookup.php?key=$api_key&input=$user_ip&output=xml";

    $response = @file_get_contents($api_url);
    if ($response === false) return [];

    $data = @simplexml_load_string($response);
    if (!$data || !isset($data->query_status)) return [];

    if ((string)$data->query_status->query_status_code === "OK") {
        return [
            'city' => (string)$data->server_data->city,
            'region' => (string)$data->server_data->region,
            'country' => (string)$data->server_data->country
        ];
    }

    return [];
}

/**
 * Détermine la meilleure localisation disponible parmi les 3 méthodes
 */
function getBestVisitorLocation() {
    $location = getVisitorLocationXML();

    if (empty($location['city']) || empty($location['latitude'])) {
        $location = getVisitorLocationJSON();
    }

    if (empty($location['city'])) {
        $location = getVisitorLocationWIP();
    }

    return $location;
}

// ========================================
// 📁 FICHIERS CSV
// ========================================

/**
 * Lit un fichier CSV et retourne un tableau associatif
 */
function lireCSV($fichier, $separateur = ",") {
    $resultat = [];

    if (!file_exists($fichier)) return [];

    if (($handle = fopen($fichier, "r")) !== false) {
        $entetes = fgetcsv($handle, 1000, $separateur);
        if (!$entetes) return [];

        while (($ligne = fgetcsv($handle, 1000, $separateur)) !== false) {
            if (count($ligne) === count($entetes)) {
                $resultat[] = array_combine($entetes, $ligne);
            }
        }
        fclose($handle);
    }

    return $resultat;
}

/**
 * Relie les données de villes avec leurs départements et régions respectifs
 */
function relierCSV($villes, $departements, $regions) {
    $departements_par_code = [];
    foreach ($departements as $dep) {
        $departements_par_code[$dep["code"]] = $dep;
    }

    $regions_par_code = [];
    foreach ($regions as $region) {
        $regions_par_code[$region["code"]] = $region;
    }

    foreach ($villes as &$ville) {
        $codeDep = $ville["code_departement"] ?? null;
        $ville["departement"] = $departements_par_code[$codeDep] ?? [];

        $codeReg = $ville["departement"]["code_region"] ?? null;
        $ville["region"] = $regions_par_code[$codeReg] ?? [];
    }

    return $villes;
}

/**
 * Récupère le nom de la région à partir d'une ville
 */
function getRegionFromVille($nomVille, $villes, $departements, $regions) {
    $nomVille = strtolower($nomVille);
    $villes = relierCSV($villes, $departements, $regions);

    foreach ($villes as $ville) {
        if (strtolower($ville["nom"] ?? '') === $nomVille) {
            return $ville["region"]["nom"] ?? "Région inconnue";
        }
    }

    return "Région inconnue";
}

/**
 * Retourne les infos de la préfecture d’un département donné
 */
function getPrefectureParDepartement($codeDept, $villes) {
    foreach ($villes as $ville) {
        if (
            isset($ville['code_departement'], $ville['est_prefecture']) &&
            $ville['code_departement'] === $codeDept &&
            strcasecmp($ville['est_prefecture'], "TRUE") === 0
        ) {
            return $ville;
        }
    }
    return null;
}

// ========================================
// 🧭 INTERFACE UTILISATEUR
// ========================================

/**
 * Affiche le menu de navigation en mettant en surbrillance la page active
 */
function afficherMenu($pageActive) {
    $pages = [
        "index" => ["Accueil", "🏠", "index.php"],
        "map" => ["Météo", "🌤️", "map.php"],
        "Statistiques" => ["Statistiques", "📊", "Statistiques.php"]
    ];

    echo '<nav><ul>';
    foreach ($pages as $cle => [$nom, $icone, $lien]) {
        $classe = ($cle === $pageActive) ? 'active' : '';
        echo "<li><a class='$classe' href='$lien'>$icone $nom</a></li>";
    }
    echo '</ul></nav>';
}
?>


