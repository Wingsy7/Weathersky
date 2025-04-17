<?php 
require "./include/header.inc.php";
require "./include/functions.inc.php";

$villesStats = file_exists("stats_villes.json") ? json_decode(file_get_contents("stats_villes.json"), true) : [];
$departementsStats = file_exists("stats_departements.json") ? json_decode(file_get_contents("stats_departements.json"), true) : [];
$mapStats = file_exists("stats_map.json") ? json_decode(file_get_contents("stats_map.json"), true) : [];

$totalVilles = array_sum($villesStats);
$totalDeps = array_sum($departementsStats);
$totalMapClicks = array_sum($mapStats);

$style_css = getStyle();
echo en_tete("Statistiques Météo", false);
echo TD_actuel_selectionné(2);
?>

<title>Statistiques</title>
<link rel="stylesheet" href="style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main>
    <div class="main-part">
        <h1>📊 Statistiques du site météo</h1>

        <section>
            <h2>🔎 Recherches par villes</h2>
            <canvas id="chartVilles" width="600" height="300"></canvas>
        </section>

        <section>
            <h2>🏩 Recherches par départements</h2>
            <canvas id="chartDeps" width="600" height="300"></canvas>
        </section>

        <section>
            <h2>🗺️ Interactions via la carte interactive</h2>
            <canvas id="chartMap" width="600" height="300"></canvas>
        </section>

        <section>
            <h2>🚗 Infos Trafic</h2>
            <div id="trafic"></div>
        </section>

        <section>
            <h2>📈 Infos Boursières</h2>
            <div id="bourse"></div>
        </section>

        <section>
            <h2>📰 Actualités Récentes</h2>
            <div id="news"></div>
        </section>

        <p><strong>Total recherches villes :</strong> <?= $totalVilles ?> | <strong>Total départements :</strong> <?= $totalDeps ?> | <strong>Clicks sur carte :</strong> <?= $totalMapClicks ?></p>

        <a href="index.php" class="btn">⬅ Retour à l'accueil</a>
    </div>
</main>

<script>
const villes = <?= json_encode($villesStats) ?>;
const deps = <?= json_encode($departementsStats) ?>;
const maps = <?= json_encode($mapStats) ?>;

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
    options: {indexAxis: 'y'}
});

new Chart(document.getElementById('chartDeps'), {
    type: 'bar',
    data: {
        labels: Object.keys(deps),
        datasets: [{
            label: 'Recherches par département',
            data: Object.values(deps),
            backgroundColor: 'rgba(255, 159, 64, 0.6)'
        }]
    },
    options: {indexAxis: 'y'}
});

new Chart(document.getElementById('chartMap'), {
    type: 'bar',
    data: {
        labels: Object.keys(maps),
        datasets: [{
            label: 'Clicks sur la carte',
            data: Object.values(maps),
            backgroundColor: 'rgba(153, 102, 255, 0.6)'
        }]
    },
    options: {indexAxis: 'y'}
});

// === Infos Trafic via geolocation + HERE Traffic API ou TomTom (simulé ici) ===
navigator.geolocation.getCurrentPosition(pos => {
    const lat = pos.coords.latitude;
    const lon = pos.coords.longitude;
    document.getElementById("trafic").innerHTML = `
        <p>📍 Position actuelle : ${lat.toFixed(3)}, ${lon.toFixed(3)}</p>
        <p>Trafic local simulé : circulation fluide ✅</p>`;
}, err => {
    document.getElementById("trafic").innerText = "Position non autorisée";
});

// === Infos Bourse ===
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
    .catch(err => {
        document.getElementById("bourse").innerText = "Erreur bourse.";
    });

// === NewsAPI ===
fetch("https://newsapi.org/v2/top-headlines?country=fr&apiKey=7d8d89965e9f4498a34f59d67cb540ea")
    .then(res => res.json())
    .then(data => {
        const container = document.getElementById("news");
        data.articles.slice(0, 5).forEach(article => {
            container.innerHTML += `
                <div class="news-card">
                    <img src="${article.urlToImage}" alt="" width="200"><br>
                    <a href="${article.url}" target="_blank">${article.title}</a>
                    <p>${article.description || ""}</p>
                </div><hr>`;
        });
    })
    .catch(err => {
        document.getElementById("news").innerText = "Erreur actus.";
    });
</script>

<?php require "./include/footer.inc.php"; ?>
