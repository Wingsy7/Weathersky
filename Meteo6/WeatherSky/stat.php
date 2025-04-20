<?php
// Lire le fichier CSV et compter les villes
$fichier = 'villes_consultées.csv';
$villes = [];

if (($handle = fopen($fichier, 'r')) !== false) {
    // Ignorer l'en-tête
    fgetcsv($handle);
    while (($data = fgetcsv($handle)) !== false) {
        $ville = htmlspecialchars($data[0]); // Protection contre injection HTML
        $villes[$ville] = ($villes[$ville] ?? 0) + 1;
    }
    fclose($handle);
}

// Préparer les données pour l'histogramme
$labels = array_keys($villes);
$valeurs = array_values($villes);
?>

<!-- Pas de <html> ou <head> ici, car ce fichier est intégré dans une structure globale -->
<!-- On suppose que le header, doctype, etc. sont déjà inclus avant -->

<!-- Contenu principal -->
<section>
    <h1>Statistiques des villes consultées</h1>

    <?php if (!empty($villes)) : ?>
        <?php $max = max($valeurs); ?>
        <div class="bars">
            <?php foreach ($villes as $ville => $count) : ?>
                <?php
                $width = ($count / $max) * 300; // Largeur max en px
                ?>
                <div class="bar" style="width: <?= (int)$width ?>px;">
                    <span><?= $ville ?></span> (<?= $count ?>)
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <p>Aucune donnée disponible.</p>
    <?php endif; ?>

    <p><a href="index.php">⬅ Retour</a></p>
</section>
 
