<?php
/**
 * Récupère les prévisions météo sur 5 jours avec toutes les informations utiles
 * depuis Open-Meteo pour une latitude et une longitude données.
 *
 * @param float $lat Latitude de la ville
 * @param float $lon Longitude de la ville
 * @return array Données météo décodées (tableau associatif)
 */
function getMeteoData(float $lat, float $lon): array {
    $url = "https://api.open-meteo.com/v1/forecast?"
        . "latitude=$lat&longitude=$lon"
        . "&daily=temperature_2m_min,temperature_2m_max,"
        . "apparent_temperature_min,apparent_temperature_max,"
        . "precipitation_sum,rain_sum,snowfall_sum,precipitation_hours,"
        . "windspeed_10m_max,winddirection_10m_dominant,"
        . "sunrise,sunset,uv_index_max,weathercode"
        . "&current_weather=true"
        . "&timezone=Europe%2FParis";

    $json = file_get_contents($url);
    return json_decode($json, true);
}

/**
 * Retourne le nom du fichier icône correspondant au code météo Open-Meteo
 *
 * @param int $code Code météo (weathercode)
 * @return string Chemin vers l’image
 */
function getWeatherIcon(int $code): string {
    $icons = [
        0 => "0-clear.png",
        1 => "1-mainly_clear.png",
        2 => "2-partly_cloudy.png",
        3 => "3-overcast.png",
        45 => "45-fog.png",
        51 => "51-drizzle.png",
        61 => "61-rain.png",
        71 => "71-snow.png",
        95 => "95-thunderstorm.png"
    ];
    return "images/weather/" . ($icons[$code] ?? "unknown.png");
}

/**
 * Récupère les données de pollution de l'air depuis l'API OpenWeatherMap
 *
 * @param float $lat Latitude
 * @param float $lon Longitude
 * @param string $apiKey Clé API OpenWeatherMap
 * @return array [int $aqi, string $aqi_text]
 */
function getAirPollution(float $lat, float $lon, string $apiKey): array {
    $url = "https://api.openweathermap.org/data/2.5/air_pollution?"
         . "lat=$lat&lon=$lon&appid=$apiKey";

    $json = file_get_contents($url);
    $data = json_decode($json, true);

    $aqi = $data['list'][0]['main']['aqi'] ?? null;
    $labels = ["", "Très bonne", "Bonne", "Moyenne", "Mauvaise", "Très mauvaise"];
    $text = $labels[$aqi] ?? "Inconnue";

    return [$aqi, $text];
}

?>