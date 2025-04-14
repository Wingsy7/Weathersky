<?php 
    $ipAdresse = getenv("REMOTE_ADDR");
    $apiKey = "LHA6vntliKABHLYaN4bbp7nsRahBtj2qlmOIpMCh";
    
    // Récupérer les données JSON de l'API NASA avec gestion d'erreur
    $apiUrl = "https://api.nasa.gov/planetary/apod?api_key=$apiKey&thumbs=true";
    $reponse = @file_get_contents($apiUrl);
    $donnee = $reponse ? json_decode($reponse, true) : null;

    // Récupérer les données de géolocalisation en XML avec gestion d'erreur
    $apiUrlXML = "http://www.geoplugin.net/xml.gp?ip=$ipAdresse";
    $reponseXml = @file_get_contents($apiUrlXML);
    $xmlData = $reponseXml ? simplexml_load_string($reponseXml) : null;

    // Récupérer les données d'IP avec ipinfo.io avec gestion d'erreur
    $urlipInfo = "https://ipinfo.io/$ipAdresse/geo";
    $reponseIpInfo = @file_get_contents($urlipInfo);
    $ipData = $reponseIpInfo ? json_decode($reponseIpInfo, true) : null;
?>
<?php 
$geo_url = "http://www.geoplugin.net/xml.gp?
ip=$ip";
$geo_xml = file_get_contents$geo_url);
if ($geo_xml) {
$geo_data =
simplexml_load_string($geo_xml);
$city = (string) ($geo_data-
>geoplugin_city?: "Inconnu");
$country = (string) ($geo_data-
›geoplugin_countryName?: "Inconnu");
} else {
$city = "Inconnu";
$country = "Inconnu";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>NASA APOD</title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Image NASA du jour</h1>
    </header>

    <main>
        <section>
            <h2>Image ou Vidéo du Jour</h2>
            <?php if ($donnee): ?>
                <h3><?= htmlspecialchars($donnee["title"]) ?></h3>
                <?php if ($donnee["media_type"] === "image"): ?>
                    <img src="<?= htmlspecialchars($donnee["url"]) ?>" alt="Image du jour APOD" />
                <?php elseif ($donnee["media_type"] === "video"): ?>
                    <iframe width="960" height="540" src="<?= htmlspecialchars($donnee["url"]) ?>" frameborder="0" allowfullscreen></iframe>
                <?php else: ?>
                    <p>Contenu non supporté.</p>
                <?php endif; ?>
                <p><?= htmlspecialchars($donnee["explanation"]) ?></p>
            <?php else: ?>
                <p>Erreur: Impossible de récupérer les données de la NASA.</p>
            <?php endif; ?>
        </section>

        <section>
            <h2>Données de géolocalisation</h2>
            <?php if ($xmlData): ?>
                <ul>
                    <li>Ville: <?= htmlspecialchars($xmlData->geoplugin_city) ?></li>
                    <li>Région: <?= htmlspecialchars($xmlData->geoplugin_region) ?></li>
                    <li>Pays: <?= htmlspecialchars($xmlData->geoplugin_countryName) ?></li>
                    <li>Latitude: <?= htmlspecialchars($xmlData->geoplugin_latitude) ?></li>
                    <li>Longitude: <?= htmlspecialchars($xmlData->geoplugin_longitude) ?></li>
                </ul>
            <?php else: ?>
                <p>Erreur: Impossible de récupérer les données de géolocalisation.</p>
            <?php endif; ?>
        </section>

        <section>
            <h2>Informations sur votre IP</h2>
            <?php if ($ipData): ?>
                <ul>
                    <li>Adresse IP: <?= htmlspecialchars($ipData['ip']) ?></li>
                    <li>Ville: <?= htmlspecialchars($ipData['city'] ?? 'Inconnue') ?></li>
                    <li>Localisation: <?= htmlspecialchars($ipData['loc'] ?? 'Inconnue') ?></li>
                </ul>
            <?php else: ?>
                <p>Erreur: Impossible de récupérer les informations de votre IP.</p>
            <?php endif; ?>
        </section>
    </main>
    
    <footer>
        <p>© 2025 - Projet en développement - <a href="tech.php">Tech</a></p>
    </footer>
</body>
</html>



