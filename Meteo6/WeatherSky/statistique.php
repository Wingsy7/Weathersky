<?php
// === Lecture du fichier JSON de visites ===
$file = 'visites.json';
$visites = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
$total = count($visites); // Total des visites

// === Calcul des statistiques par ville ===
$villes = [];
foreach ($visites as $v) {
    $ville = $v['ville'] ?? 'Inconnue';
    $villes[$ville] = ($villes[$ville] ?? 0) + 1;
}
arsort($villes); // Trie les villes par nombre de visites décroissant
$villesTop = array_slice($villes, 0, 5); // Top 5
file_put_contents("stats_villes.json", json_encode($villesTop)); // Sauvegarde

// Préparation pour les graphiques JS
$villesStats = $villesTop;

// === Coordonnées de la dernière position utilisateur ===
$lat = $_GET['lat'] ?? $_COOKIE['derniere_lat'] ?? null;
$lon = $_GET['lon'] ?? $_COOKIE['derniere_lon'] ?? null;

// === Inclusions ===
require "./include/header.inc.php"; // Contient doctype + <html> + <head> + <body>
require "./include/functions.inc.php";

// Récupération du style choisi (clair/sombre)
$style_css = getStyle();
echo en_tete("Statistiques Météo", false); // Affiche le <title> mais NE ferme pas <head>
?>

<!-- Contenu propre à cette page -->
<link rel="stylesheet" href="style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<main class="main-part">
    <h1>📊 Statistiques du site météo</h1>

    <p><strong>Total de visiteurs :</strong> <?= $total ?></p>

    <section>
        <h2>🏆 Top 5 des villes les plus visitées</h2>
        <canvas id="sitogramme" width="600" height="300"></canvas>
    </section>

    <section>
        <h2>🔎 Recherches par villes</h2>
        <canvas id="chartVilles" width="600" height="300"></canvas>
    </section>

    <section>
        <h2>🚗 Infos Trafic</h2>
        <div id="trafic">
            <?php if ($lat && $lon): ?>
                <?php
                $destLat = $lat + 0.3;
                $destLon = $lon + 0.3;
                $apiKey = "2806193fb62e4354b35df62f6399de95";
                $url = "https://api.geoapify.com/v1/routing?waypoints=$lat,$lon|$destLat,$destLon&mode=drive&apiKey=$apiKey";
                $response = @file_get_contents($url);

                if ($response !== false) {
                    $data = json_decode($response, true);
                    $props = $data['features'][0]['properties'] ?? null;

                    if ($props) {
                        echo "<p>📍 Position : " . round($lat, 3) . ", " . round($lon, 3) . "</p>";
                        echo "<p>📏 Distance : " . round($props['distance'] / 1000, 2) . " km</p>";
                        echo "<p>🕒 Durée estimée : " . round($props['time'] / 60) . " min</p>";
                    } else {
                        echo "<p>Erreur dans les données de Geoapify.</p>";
                    }
                } else {
                    echo "<p>Erreur de connexion à Geoapify.</p>";
                }
                ?>
            <?php else: ?>
                <p>Aucune position détectée (coordonnées non fournies).</p>
            <?php endif; ?>
        </div>
    </section>

    <section>
        <h2>📈 Infos Boursières</h2>
        <div id="bourse">Chargement...</div>
    </section>

    <a href="index.php" class="btn">⬅ Retour à l'accueil</a>
</main>

<!-- Graphiques + API Bourse -->
<script>
const villesTop = <?= json_encode($villesTop) ?>;
const villes = <?= json_encode($villesStats) ?>;

// === Sitogramme Top 5 villes ===
new Chart(document.getElementById('sitogramme'), {
    type: 'bar',
    data: {
        labels: Object.keys(villesTop),
        datasets: [{
            label: 'Nombre de recherches',
            data: Object.values(villesTop),
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        indexAxis: 'y',
        scales: { y: { beginAtZero: true } },
        plugins: {
            title: {
                display: true,
                text: 'Top 5 des villes les plus recherchées'
            }
        }
    }
});

// === Recherches par ville (toutes) ===
new Chart(document.getElementById('chartVilles'), {
    type: 'bar',
    data: {
        labels: Object.keys(villes),
        datasets: [{
            label: 'Recherches par ville',
            data: Object.values(villes),
            backgroundColor: 'rgba(75, 192, 192, 0.6)'
        }]
    },
    options: { indexAxis: 'y' }
});

// === Infos Bourse via AlphaVantage ===
fetch("https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=AAPL&apikey=JIXHOJRIUWMYR26Q")
    .then(res => res.json())
    .then(data => {
        const quote = data["Global Quote"];
        if (quote) {
            document.getElementById("bourse").innerHTML = `
                <p><strong>${quote["01. symbol"]}</strong></p>
                <p>Prix : ${quote["05. price"]} $</p>
                <p>Variation : ${quote["10. change percent"]}</p>`;
        }
    })
    .catch(() => {
        document.getElementById("bourse").innerText = "Erreur bourse.";
    });
</script>

<?php include("include/footer.inc.php"); ?>
