<?php
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
  $user_ip = getUserIP();
  $api_url = "https://ipinfo.io/$user_ip/json";

  $response = file_get_contents($api_url);
  $data = json_decode($response, true);

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

  $response = file_get_contents($api_url);
  $data = simplexml_load_string($response);

  if (isset($data->query_status) && $data->query_status->query_status_code == "OK") {
      $location = [
          'city' => (string)$data->server_data->city,
          'region' => (string)$data->server_data->region,
          'country' => (string)$data->server_data->country
      ];
      return $location;
  }
}

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

function getVisitorLocationFromWhatIsMyIP() {
  $user_ip = getUserIP();
  $api_key = "e964f74aadfe25702230dfcbac03a675";
  $api_url = "https://api.whatismyip.com/ip-address-lookup.php?key=$api_key&input=$user_ip&output=xml";

  $response = file_get_contents($api_url);
  $data = simplexml_load_string($response);

  $location = [
      'city' => (string)$data->city,
      'region' => (string)$data->region,
      'country' => (string)$data->country
  ];

  return $location;
}

function lireCSV($fichier, $separateur = ",") {
  $resultat = [];

  if (!file_exists($fichier)) {
      return [];
  }

  if (($handle = fopen($fichier, "r")) !== false) {
      $entetes = fgetcsv($handle, 1000, $separateur);
      if (!$entetes) {
          return [];
      }

      while (($ligne = fgetcsv($handle, 1000, $separateur)) !== false) {
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

function getWeatherEmoji($code) {
  switch ($code) {
    case 0: return "☀️";      // Clair
    case 1: return "🌤️";     // Peu nuageux
    case 2: return "⛅";      // Partiellement nuageux
    case 3: return "☁️";      // Nuageux
    case 45: return "🌫️";    // Brouillard
    case 48: return "🌫️";
    case 51: return "🌦️";    // Bruine légère
    case 53: return "🌦️";    // Bruine modérée
    case 55: return "🌦️";    // Bruine dense
    case 61: return "🌧️";    // Pluie faible
    case 63: return "🌧️";    // Pluie modérée
    case 65: return "🌧️";    // Pluie forte
    case 71: return "🌨️";    // Neige faible
    case 73: return "🌨️";    // Neige modérée
    case 75: return "🌨️";    // Neige forte
    case 95: return "⛈️";    // Orage
    default: return "❓";     // Code inconnu
  }
}

function get_browser_info(): string {
  $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Inconnu';

  if (stripos($user_agent, 'Firefox') !== false) {
      return 'Mozilla Firefox';
  } elseif (stripos($user_agent, 'Chrome') !== false) {
      return 'Google Chrome';
  } elseif (stripos($user_agent, 'Safari') !== false) {
      return 'Apple Safari';
  } elseif (stripos($user_agent, 'Edge') !== false) {
      return 'Microsoft Edge';
  } elseif (stripos($user_agent, 'MSIE') !== false || stripos($user_agent, 'Trident') !== false) {
      return 'Internet Explorer';
  } else {
      return 'Navigateur inconnu';
  }
}

?>


