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
            <h2>🏛️ Recherches par départements</h2>
            <canvas id="chartDeps" width="600" height="300"></canvas>
        </section>

        <section>
            <h2>🗺️ Interactions via la carte interactive</h2>
            <canvas id="chartMap" width="600" height="300"></canvas>
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
</script>

<?php require "./include/footer.inc.php"; ?>
