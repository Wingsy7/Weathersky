<?php
declare(strict_types = 1);
define('DEFAULT_WIDTH', 10);
define('DEFAULT_HEIGHT', 10);
define("DEFAULT_LIST_TYPE", 0);
define("DAY", date("l"));
define("MONTH", date("F"));

/* TD 5 */
/* TD 5 exercice 1 */
/**
 * Génère une table ASCII en HTML avec des styles pour différencier les chiffres, majuscules et minuscules.
 * 
 * @return string affichage de l'heure actuelle du serveur.
 */
function afficherHeure(): string{
    $h="L'heure du serveur est : " . date("H:i:s");
    return $h;
}

/* TD 5 exercice 2 */
/**
 * Génère une table ASCII en HTML avec des styles pour différencier les chiffres, majuscules et minuscules.
 * 
 * @return string une liste HTML non ordonnée de 20 éléments
 */
function genererListe(): string {
    $resultat = "<ul>"; // Initialisation de la variable pour stocker les lignes
    for ($i = 1; $i <= 20; $i++) {
        $resultat .= "<li>Hello number $i</li>"; // Concaténation de chaque ligne à la variable
    }
    $resultat .= "</ul>"; // Ajout de la balise de fermeture à la fin
    return $resultat; // Retourne le résultat complet
}


/* TD 5 exercice 3 */
/**
 * Génère une table ASCII en HTML avec des styles pour différencier les chiffres, majuscules et minuscules.
 * 
 * @return string les conversions: 0x41 = 65 = 'A'
 */
function conversions(): string {
    $hex_value = '0x41';
    $dec_value = hexdec($hex_value);
    $char_value = chr($dec_value);

    $hexdec = "Conversion de 0x41 en décimal: $dec_value\n";
    $hexascii = "Conversion de 0x41 en caractère ASCII: $char_value\n";

    // Conversion 0x2B = 43 = '+'
    $hex_value = '0x2B';
    $dec_value = hexdec($hex_value);
    $char_value = chr($dec_value);
    
    $hexdec2 = "Conversion de 0x2B en décimal: $dec_value\n";
    $hexascii2 = "Conversion de 0x2B en caractère ASCII: $char_value\n";

    // Conversion inverse
    $char = '+';
    $dec_value = ord($char);
    $hex_value = dechex($dec_value);
    
    $chardec = "Conversion du caractère '+' en décimal: $dec_value\n";
    $charhex = "Conversion du caractère '+' en hexadécimal: 0x$hex_value\n";

    // Retourner toutes les conversions
    return $hexdec . $hexascii . $hexdec2 . $hexascii2 . $chardec . $charhex;
}


/* TD 5 exercice 4 */
/**
 * Génère une table ASCII en HTML avec des styles pour différencier les chiffres, majuscules et minuscules.
 * 
 * @return string une liste HTML non ordonnée des chiffres hexadécimaux de 0 à F
 */
function liste_hexa(): string {
    $resultat = "<ul style='list-style-type:none; display:flex;'>"; // Initialisation de la variable pour stocker les lignes
    for ($i = 0; $i <= 15; $i++) {
        $resultat .= "<li style='margin-right:10px;'>" . dechex($i) . "</li>"; // Concaténation de chaque ligne à la variable
    }
    $resultat .= "</ul>"; // Ajout de la balise de fermeture à la fin
    return $resultat; // Retourne le résultat complet
}

/* TD 5 exercice 5 */
/**
 * Génère une table ASCII en HTML avec des styles pour différencier les chiffres, majuscules et minuscules.
 * 
 * @return string un tableau HTML avec tous les nombres de 0 à 17 en bases 2, 8, 10, 16 (binaire, octal, décimal, hexadécimal
 */
function genererTableauConversion(): string {
    $resultat = "<table>
            <caption>Tableau Des Conversions</caption>
            <thead>
                <tr>
                    <th>Binaire</th>
                    <th>Décimal</th>
                    <th>Octal</th>
                    <th>Hexadécimal</th>
                </tr>
            </thead>
            <tbody>"; // Initialisation de la variable pour stocker les lignes
    for ($i = 0; $i <= 17; $i++) {
        $resultat .= "<tr>
                <td>" . sprintf("%b", $i) . "</td>
                <td>$i</td>
                <td>" . sprintf("%o", $i) . "</td>
                <td>" . sprintf("%X", $i) . "</td>
            </tr>"; // Concaténation de chaque ligne à la variable
    }
    $resultat .= "</tbody></table>"; // Ajout de la balise de fermeture à la fin
    return $resultat; // Retourne le résultat complet
}


/* TD 6 */
/* TD 6 exercice 1 */
/**
 * Génère une table de multiplication HTML de taille personnalisée dans la balise <table>.
 * 
 * @param int $width Largeur de la table, DEFAULT_WIDTH par défaut.
 * @param int $height Hauteur de la table, DEFAULT_HEIGHT par défaut.
 * @return string Table de multiplication en HTML.
 */

function generTableMultiplication(int $width = DEFAULT_WIDTH, int $height = DEFAULT_HEIGHT): string {
    // initialisation de la table de multiplication
    $html = "<table class='custom-border'>";
    $html .= "<caption>Table de multiplication de $width x $height</caption>";
    $html .= "<thead><tr><th>*</th>";

    for ($i = 1; $i <= $width; $i++) {
        $html .= "<th>$i</th>";
    }
    $html .= "</tr></thead><tbody>";
    
    // Boucle pour générer les lignes de la table
    for ($row = 1; $row <= $height; $row++) {
        $html .= "<tr><th scope='row'>$row</th>";
        for ($col = 1; $col <= $width; $col++) {
            $product = $row * $col;
            $html .= "<td>$product</td>";
        }
        $html .= "</tr>";
    }
    $html .= "</tbody></table>";
    return $html;
}

/* TD 6 exercice 2 */
/**
 * Génère une table ASCII en HTML avec des styles pour différencier les chiffres, majuscules et minuscules.
 * 
 * @return string La chaîne HTML représentant la table ASCII standard avec mise en forme CSS.
 */
function generTableAscii(): string {
    $tableAsciiHtml = "<table class='custom-border'>";
    $tableAsciiHtml .= "<caption>Table ASCII (Sans caractères spéciaux)</caption><thead><tr>";

    // Création des en-têtes de colonnes
    for ($col = 0; $col < 8; $col++) {
        $tableAsciiHtml .= "<th scope='col'>Code</th><th scope='col'>Char</th>";
    }
    $tableAsciiHtml .= "</tr></thead><tbody>";

    // Boucle pour générer la table ASCII, en sautant les caractères spéciaux
    for ($i = 32; $i < 128; $i += 8) {
        $tableAsciiHtml .= "<tr>";

        for ($j = $i; $j < $i + 8 && $j < 128; $j++) {
            // Vérifier si c'est un chiffre, une lettre majuscule ou minuscule
            if (($j >= 48 && $j <= 57) || ($j >= 65 && $j <= 90) || ($j >= 97 && $j <= 122)) {
                $char = htmlspecialchars(chr($j), ENT_QUOTES | ENT_HTML5, 'UTF-8');

                // Définir la classe CSS en fonction du type de caractère
                $class = '';
                if ($j >= 48 && $j <= 57) { // Chiffres
                    $class = 'digit';
                } elseif ($j >= 65 && $j <= 90) { // Majuscules
                    $class = 'uppercase';
                } elseif ($j >= 97 && $j <= 122) { // Minuscules
                    $class = 'lowercase';
                }

                // Ajouter les cellules avec les classes CSS appropriées
                $tableAsciiHtml .= "<td class='$class'>$j</td><td class='$class'>$char</td>";
            } else {
                // Si ce n'est pas un chiffre ou une lettre, on saute ce caractère
                $tableAsciiHtml .= "<td></td><td></td>";
            }
        }

        $tableAsciiHtml .= "</tr>";
    }

    $tableAsciiHtml .= "</tbody></table>";
    return $tableAsciiHtml;
}


/* TD 7 */
/* TD 7 exercice 1 */
/**
 * Crée une liste contenant toutes les régions de France
 * 
 * @param type_liste_demande Un entier indiquant si la liste doit être ordonnée ou non
 * @return s La liste des régions de France
 */
function parcourir_regions(int $type_liste_demande = DEFAULT_LIST_TYPE): string {
    $france_regions = array(
        "Guadeloupe", "Martinique", "Guyane", "La Réunion", "Mayotte", 
        "Île-de-France", "Centre-Val de Loire", "Bourgogne-Franche-Comté", 
        "Normandie", "Hauts-de-France", "Grand Est", "Pays de la Loire", 
        "Bretagne", "Nouvelle-Aquitaine", "Occitanie", "Auvergne-Rhône-Alpes", 
        "Provence-Alpes-Côte d’Azur", "Corse"
    );

    $type_liste = ($type_liste_demande == DEFAULT_LIST_TYPE) ? "ol" : "ul";
    $s = "<$type_liste>";

    foreach ($france_regions as $region) {
        $s .= "\n\t<li>$region</li>";
    }

    $s .= "</$type_liste>";
    return $s;
}


/* TD 7 exercice 2 */
/**
 * Obtient l'origine étymologique d'un nom donné (jour de la semaine ou mois de l'année).
 * 
 * @param string $nom Le nom du jour de la semaine ou du mois de l'année.
 * @return string L'origine étymologique associée au nom donné.
 */
function obtenir_origine_etymologique(string $nom): string {
    global $origines_jours, $origines_mois;
    
    // Vérifier si le nom donné est un jour de la semaine
    if (array_key_exists($nom, $origines_jours)) {
        return $origines_jours[$nom];
    }
    
    // Vérifier si le nom donné est un mois de l'année
    if (array_key_exists($nom, $origines_mois)) {
        return $origines_mois[$nom];
    }
    
    // Si le nom donné ne correspond ni à un jour ni à un mois, retourner une chaîne vide
    return "";
}



/* TD 8 */
/* TD 8 exercice 1 */
/**
 * Extrait les composantes principales d'une URL donnée.
 * 
 * @param string $url L'URL à analyser.
 * @return array Un tableau associatif contenant les composantes extraites.
 */
function extraireComposantesURL(string $url): array {
  // Analyse l'URL avec la fonction parse_url()
  $parsed_url = parse_url($url);

  // Tableau pour stocker les composantes extraites
  $composantes = array();

  // Protocole
  $composantes['protocole'] = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';

  // Hôte (organisme / sous-domaine)
  $composantes['hote'] = isset($parsed_url['host']) ? $parsed_url['host'] : '';

  // Domaine (nom de la machine)
  $host_parts = explode('.', $composantes['hote']);
  $composantes['domaine'] = isset($host_parts[1]) ? $host_parts[1] : '';

  // TLD (Top Level Domain)
  $tld = isset($host_parts[2]) ? $host_parts[2] : '';

  // Tableau associatif des TLD autorisés
  $allowed_tlds = array(
    'com' => 'Commercial',
    'org' => 'Organisation',
    'net' => 'Network',
    'fr' => 'France',
    // ... (Ajoutez d'autres TLD si nécessaire)
  );

  $composantes['tld'] = isset($allowed_tlds[$tld]) ? $allowed_tlds[$tld] : $tld;

  // Sous-domaine
  if (count($host_parts) > 2) {
    $composantes['sous-domaine'] = implode('.', array_slice($host_parts, 0, -2));
  } else {
    $composantes['sous-domaine'] = '';
  }

  // Nom du serveur (facultatif)
  if (isset($parsed_url['port'])) {
    $composantes['nom_serveur'] = $composantes['hote'] . ':' . $parsed_url['port'];
  } else {
    $composantes['nom_serveur'] = $composantes['hote'];
  }

  return $composantes;
}


/* TD 8 exercice 2 */
/**
 * Convertit une valeur octale en droits de fichier UNIX.
 * 
 * @param int $octal La valeur octale à convertir.
 * @return string Les droits de fichier associés à la valeur octale.
 */
function convertirOctalEnDroitsUnix(int $octal): string {
    // Vérifier si la valeur octale est valide (comprise entre 0 et 777)
    if ($octal < 0 || $octal > 777) {
      return "Valeur octale invalide";
    }
     
    // Définition des droits pour chaque position
    $droits = array(
      ' ---', ' --x', ' -w-', ' -wx', ' r--', ' r-x', ' rw-', ' rwx'
    );
  
    // Séparation des chiffres de l'octal
    $chiffres = str_split(str_pad((string)$octal, 3, '0', STR_PAD_LEFT), 1);
  
    // Construction de la chaîne de droits
    $resultat = '';
    foreach ($chiffres as $chiffre) {
      $resultat .= $droits[$chiffre];
    }
  
    return $octal . " => " . $resultat;
}
/* TD 9 */
/* TD 9 exercice 5 */
/**
 * Fonction pour générer la table de multiplication d'un nombre donné.
 * 
 * @param int $nombre Le nombre dont on veut générer la table de multiplication.
 * @return array La table de multiplication du nombre donné.
 */
function genererTableMultiplication(int $nombre): array {
    // Validation du nombre
    if (!is_numeric($nombre) || $nombre < 1) {
        return "Valeur invalide. Veuillez saisir un nombre positif.";
    }

    // Tableau pour stocker la table de multiplication
    $tableMultiplication = array();

    // Génération de la table de multiplication
    for ($i = 1; $i <= 10; $i++) {
        $tableMultiplication[$i] = $nombre * $i;
    }

    return $tableMultiplication;
}

/*TD 9 exercice 6 */
/*Voir TD 8 exercice 1 */

/* TD 9 exercice 7 */
/**
 * Convertit une valeur octale en permissions "d/-" et "rwx".
 * 
 * @param int $valeur_octale La valeur octale à convertir.
 * @param string $type_element Le type d'élément ("fichier" ou "repertoire").
 * @return string Les permissions formatées.
 */
function convertirOctalEnPermissions(int $valeur_octale, string $type_element): string {
    // Vérifier si la valeur octale est valide (comprise entre 0 et 777)
    if ($valeur_octale < 0 || $valeur_octale > 777) {
      return "Valeur octale invalide";
    }
  
    // Définir les droits pour chaque position
    $droits_fichier = array(
      '---', '--x', '-w-', '-wx', 'r--', 'r-x', 'rw-', 'rwx'
    );
    $droits_repertoire = array(
      '---', '--x', '-w-', '-wx', 'dr--', 'dr-x', 'drwx', 'drwx'
    );
  
    // Séparation des chiffres de l'octal
    $chiffres = str_split(str_pad((string)$valeur_octale, 3, '0', STR_PAD_LEFT), 1);
  
    // Construction de la chaîne de permissions
    $resultat = '';
    $droits = $droits_fichier;
    if ($type_element === "repertoire") {
      $droits = $droits_repertoire;
      $resultat .= 'd'; // Ajout du préfixe "d" pour les répertoires
    }
    foreach ($chiffres as $chiffre) {
      $resultat .= $droits[$chiffre];
    }
  
    return $resultat;
  }

/* TD 9 exercice 8 */
/**
 * Fonction pour générer la liste déroulante des années.
 * 
 * @param int $anneeMin L'année minimum à afficher.
 * @param int $anneeMax L'année maximum à afficher.
 * @return string Le code HTML de la liste déroulante.
 */
function genererListeAnnees(int $anneeMin, int $anneeMax) {
    $html = "";
    for ($annee = $anneeMin; $annee <= $anneeMax; $annee++) {
      $html .= "<option value=\"$annee\">$annee</option>";
    }
    return $html;
  }
  
  /**
   * Fonction pour déterminer si une année est bissextile.
   * 
   * @param int $annee L'année à tester.
   * @return bool True si l'année est bissextile, false sinon.
   */
  function estBissextile(int $annee) {
    return ($annee % 4 === 0 && ($annee % 100 !== 0 || $annee % 400 === 0));
  }
  
  /**
   * Fonction pour obtenir le jour de la semaine du 1er janvier.
   * 
   * @param int $annee L'année à tester.
   * @return int Le numéro du jour de la semaine (0 pour dimanche, 6 pour samedi).
   */
  function getJourSemaine(int $annee) {
    $timestamp = mktime(0, 0, 0, 1, 1, $annee);
    return date('w', $timestamp);
  }




// **TD 9 
// **TD 9 exercice 4**
/**
 * Fonction pour incrémenter le compteur et retourner la phrase.
 * 
 * @param string $fichier Le nom du fichier texte contenant le compteur.
 * @return string La phrase "Nombre de hits : " . $compteur_nouveau . "</p>".
 */
function CompteurHits(string $fichier): string {

  // Ouvrir le fichier en lecture/écriture
  $fichier_handle = fopen($fichier, "r+");

  // Si le fichier n'existe pas, le créer et initialiser le compteur à 0
  if (!$fichier_handle) {
    file_put_contents($fichier, "0");
    $fichier_handle = fopen($fichier, "r+");
  }

  // Lire la valeur actuelle du compteur
  $compteur_actuel = fgets($fichier_handle);

  // Incrémenter la valeur du compteur
  $compteur_nouveau = $compteur_actuel + 1;

  // Repositionner le curseur au début du fichier
  fseek($fichier_handle, 0);

  // Convertir l'entier en chaîne de caractères
  $dataToWrite = strval($compteur_nouveau);

  // Écrire la nouvelle valeur du compteur dans le fichier
  fwrite($fichier_handle, $dataToWrite);

  // Fermer le fichier
  fclose($fichier_handle);

  // Créer la phrase
  $phrase = "<p>Nombre de hits : " . $compteur_nouveau . "</p>";

  // Retourner la phrase
  return $phrase;
}


// **TD 9 exercice 5**
/**
 * Charge les données depuis un fichier CSV et les retourne sous forme de tableau.
 * 
 * @param string $cheminFichier Le chemin complet vers le fichier CSV.
 * @return array Un tableau contenant les données du fichier CSV, où chaque ligne est un tableau associatif.
 */
function chargerDonneesCSV($cheminFichier): array {
  $donnees = [];
  if (($handle = fopen($cheminFichier, "r")) !== FALSE) {
      $entetes = fgetcsv($handle); // Lire les entêtes
      while (($data = fgetcsv($handle)) !== FALSE) {
          $donnees[] = array_combine($entetes, $data);
      }
      fclose($handle);
  }
  return $donnees;
}
/**Projet
*Point D'avancement 1
*/
function getTodayDate() {
  // Récupère la date du jour au format "AAAA-MM-JJ"
  $formatted_date = date("Y-m-d");
  return $formatted_date;
}

function getAPODImage(): string {
  $api_key = "DEMO_KEY"; // Remplace par ta propre clé API
  $date = getTodayDate(); // Récupère la date du jour

  // Construis l'URL de l'API APOD
  $url = "https://api.nasa.gov/planetary/apod?api_key=$api_key&date=$date";

  // Récupère les données JSON depuis l'API
  $response = @file_get_contents($url);
  if ($response === false) {
      return '<p>Impossible de récupérer l’image du jour. Veuillez réessayer plus tard.</p>';
  }

  $data = json_decode($response, true);
  if (!$data || !isset($data['media_type']) || !isset($data['url'])) {
      return '<p>Données invalides reçues depuis l’API NASA.</p>';
  }

  if ($data['media_type'] === 'video') {
      // Affiche la vidéo
      $apod = '<div class="apod-video">';
      $apod .= '<iframe width="560" height="315" src="' . $data['url'] . '" frameborder="0" allowfullscreen></iframe>';
      $apod .= '<p>' . htmlspecialchars($data['title'] ?? '') . '</p>';
      $apod .= '<p>' . htmlspecialchars($data['explanation'] ?? '') . '</p>';
      $apod .= '</div>';
  } else {
      // Affiche l'image
      $apod = '<figure>';
      $apod .= '<img src="' . htmlspecialchars($data['url']) . '" alt="APOD Image" class="apodNasa">';
      $apod .= '<figcaption>' . htmlspecialchars($data['explanation']) . '</figcaption>';
      $apod .= '</figure>';
  }

  return $apod;
}

function getVisitorLocationXML() {
$api_url = 'http://www.geoplugin.net/xml.gp?ip=' . $_SERVER['REMOTE_ADDR'];
$response = file_get_contents($api_url);
$data = simplexml_load_string($response);

$location = [
    'city' => (string)$data->geoplugin_city,
    'region' => (string)$data->geoplugin_region,
    'country' => (string)$data->geoplugin_countryName,
    'latitude' => (float)$data->geoplugin_latitude,
    'longitude' => (float)$data->geoplugin_longitude
];

return $location;
}

function getUserIP() {
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
      $ip = $_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}
function getVisitorLocationJSON() {
  $user_ip = getUserIP(); // Récupère l'adresse IP de l'internaute
  $api_url = "https://ipinfo.io/$user_ip/json"; // Utilisez l'URL de l'API ipinfo.io

  // Récupère les données JSON depuis l'API
  $response = file_get_contents($api_url);
  $data = json_decode($response, true);

  // Extrait les informations pertinentes (ville, région, pays, latitude, longitude)
  $location = [
      'city' => $data['city'],
      'region' => $data['region'],
      'country' => $data['country'],
      'latitude' => (float)$data['loc'] ? explode(',', $data['loc'])[0] : null,
      'longitude' => (float)$data['loc'] ? explode(',', $data['loc'])[1] : null
  ];

  return $location;
}

function getVisitorLocationWIP() {
  $user_ip = getUserIP();
  $api_key = "e964f74aadfe25702230dfcbac03a675";
  $api_url = "https://api.whatismyip.com/ip-address-lookup.php?key=$api_key&input=$user_ip&output=xml";

  // Récupère les données XML depuis l'API
  $response = file_get_contents($api_url);
  $data = simplexml_load_string($response);

  if(isset($data->query_status) && $data->query_status->query_status_code == "OK") {
    // Extrait les informations pertinentes
    $location = [
        'city' => (string)$data->server_data->city,
        'region' => (string)$data->server_data->region,
        'country' => (string)$data->server_data->country,
        // Ajoutez d'autres informations si nécessaire
    ];

  return $location;
  }
}
function getBestVisitorLocation() {
  $location = getVisitorLocationXML();
  
  // Fallback vers JSON si XML n’a rien donné
  if (empty($location['city']) || empty($location['latitude'])) {
      $location = getVisitorLocationJSON();
  }

  // Fallback vers WIP en dernier recours
  if (empty($location['city'])) {
      $location = getVisitorLocationWIP();
  }

  return $location;
}

function getVisitorLocationFromWhatIsMyIP() {
  $user_ip = getUserIP();
  $api_key = "e964f74aadfe25702230dfcbac03a675";
  $api_url = "https://api.whatismyip.com/ip-address-lookup.php?key=$api_key&input=$user_ip&output=xml";

  // Récupère les données XML depuis l'API
  $response = file_get_contents($api_url);
  $data = simplexml_load_string($response);

  // Extrait les informations pertinentes (ville, région, pays, etc.)
  $location = [
      'city' => (string)$data->city,
      'region' => (string)$data->region,
      'country' => (string)$data->country,
      // Ajoutez d'autres informations si nécessaire
  ];

  return $location;
}
function lireCSV($fichier, $separateur = ",") {
  $resultat = [];

  if (!file_exists($fichier)) {
      return []; // fichier manquant = tableau vide
  }

  if (($handle = fopen($fichier, "r")) !== false) {
      $entetes = fgetcsv($handle, 1000, $separateur);
      if (!$entetes) {
          return []; // pas de ligne d'en-tête
      }

      while (($ligne = fgetcsv($handle, 1000, $separateur)) !== false) {
          // Vérifie que la ligne a le bon nombre de colonnes
          if (count($ligne) === count($entetes)) {
              $resultat[] = array_combine($entetes, $ligne);
          }
      }
      fclose($handle);
  }

  return $resultat;
}
function getRegionFromVille($codeINSEE, $villes, $departements, $regions) {
  foreach ($villes as $ville) {
      if ($ville[0] == $codeINSEE) {
          $depCode = $ville[3];
          foreach ($departements as $dep) {
              if ($dep['DEP'] == $depCode) {
                  $regCode = $dep['REG'];
                  foreach ($regions as $region) {
                      if ($region['REG'] == $regCode) {
                          return $region['LIBELLE'];
                      }
                  }
              }
          }
      }
  }
  return "Région inconnue";
}


?>
