<?php 
require_once('include/functions.inc.php');
include("include/header.inc.php");
echo en_tete("tech");

$ipAdresse = $_SERVER["REMOTE_ADDR"] ?? '127.0.0.1';
$apiKey = "LHA6vntliKABHLYaN4bbp7nsRahBtj2qlmOIpMCh";

// Données NASA
$apiUrl = "https://api.nasa.gov/planetary/apod?api_key=$apiKey&thumbs=true";
$reponse = @file_get_contents($apiUrl);
$donnee = $reponse ? json_decode($reponse, true) : null;

// Géolocalisation XML
$apiUrlXML = "http://www.geoplugin.net/xml.gp?ip=$ipAdresse";
$reponseXml = @file_get_contents($apiUrlXML);
$xmlData = $reponseXml ? simplexml_load_string($reponseXml) : null;

// IPInfo
$urlipInfo = "https://ipinfo.io/$ipAdresse/geo";
$reponseIpInfo = @file_get_contents($urlipInfo);
$ipData = $reponseIpInfo ? json_decode($reponseIpInfo, true) : null;
?>

<main class="main-part">
    <h1>Image NASA du jour</h1>

    <section>
        <h2>Image ou Vidéo du Jour</h2>
        <?php if ($donnee): ?>
            <h3><?= htmlspecialchars($donnee["title"]) ?></h3>
            <?php if ($donnee["media_type"] === "image"): ?>
                <img src="<?= htmlspecialchars($donnee["url"]) ?>" alt="Image du jour APOD">
            <?php elseif ($donnee["media_type"] === "video"): ?>
                <iframe width="560" height="315" src="<?= htmlspecialchars($donnee["url"]) ?>" frameborder="0" allowfullscreen></iframe>
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
                <li>Adresse IP: <?= htmlspecialchars($ipData['ip'] ?? $ipAdresse) ?></li>
                <li>Ville: <?= htmlspecialchars($ipData['city'] ?? 'Inconnue') ?></li>
                <li>Localisation: <?= htmlspecialchars($ipData['loc'] ?? 'Inconnue') ?></li>
            </ul>
        <?php else: ?>
            <p>Erreur: Impossible de récupérer les informations de votre IP.</p>
        <?php endif; ?>
    </section>

    <section>
        <h2>Informations sur votre navigateur</h2>
        <p id="browser-info">Navigateur actuel: </p>
    </section>
</main>

<?php include("include/footer.inc.php"); ?>


