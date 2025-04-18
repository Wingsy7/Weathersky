<?php
$file = 'visites.json';
$visites = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

$total = count($visites);

// Compte les villes
$villes = [];
foreach ($visites as $v) {
    $ville = $v['ville'] ?? 'Inconnue';
    $villes[$ville] = ($villes[$ville] ?? 0) + 1;
}

arsort($villes);

// On prend les 5 villes principales
$villesTop = array_slice($villes, 0, 5);

// Sauvegarde les données dans un fichier JSON pour le JavaScript
file_put_contents("stats_villes.json", json_encode($villesTop));
?>

<?php include("include/header.inc.php"); ?>
<?= en_tete("Statistiques"); ?>

<main class="main-part">
    <h1>Statistiques du site</h1>
    <p><strong>Total de visiteurs :</strong> <?= $total ?></p>

    <h2>Top 5 des villes les plus visitées</h2>

    <!-- Sitogramme des villes les plus recherchées -->
    <canvas id="sitogramme" width="600" height="300"></canvas>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php
    $stats = json_decode(file_get_contents("stats_villes.json"), true);
    $labelsJS = json_encode(array_keys($stats));
    $dataJS = json_encode(array_values($stats));
    ?>
    <script>
        const ctx = document.getElementById('sitogramme').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= $labelsJS ?>,
                datasets: [{
                    label: 'Nombre de recherches',
                    data: <?= $dataJS ?>,
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
                        text: 'Sitogramme des villes les plus recherchées'
                    }
                }
            }
        });
    </script>

    <a href="index.php">Retour</a>
</main>

<?php include("include/footer.inc.php"); ?>




