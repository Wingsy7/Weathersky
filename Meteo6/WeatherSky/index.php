<?php
// Active l'affichage des erreurs PHP pour le développement
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Enregistrement de la visite avec IP et géolocalisation
$ip = $_SERVER['REMOTE_ADDR'];
$data = @json_decode(file_get_contents("http://ip-api.com/json/$ip"), true);

$ville = $data['city'] ?? 'Inconnue';
$pays = $data['country'] ?? 'Inconnu';

// Chargement du fichier JSON de visites (ou tableau vide s'il n'existe pas)
$file = 'visites.json';
$visites = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

// Ajout d'une nouvelle entrée de visite
$visites[] = [
    'ip' => $ip,
    'ville' => $ville,
    'pays' => $pays,
    'date' => date('Y-m-d H:i:s')
];

// Sauvegarde des visites dans le fichier JSON
file_put_contents($file, json_encode($visites, JSON_PRETTY_PRINT));

// Inclusion de l'en-tête HTML
if (!file_exists("include/header.inc.php")) {
    die("Erreur : include/header.inc.php introuvable");
}
include("include/header.inc.php");

// Affichage de l'en-tête de page
echo en_tete("Accueil");
echo "<!-- Header inclus -->";

// Récupération des images présentes dans le dossier 'photo/'
$photosDir = 'photo/';
$images = [];

if (is_dir($photosDir)) {
    $files = scandir($photosDir);
    foreach ($files as $file) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif']) && is_file($photosDir . $file)) {
            $images[] = $file;
        }
    }
}

// Sélection d'une image aléatoire (non utilisée ici, mais conservée si utile plus tard)
$randomImage = !empty($images) ? $images[array_rand($images)] : null;
?>

<!-- Titre principal du site avec logo -->
<h1 class="site-title">
<?php if (file_exists("logo/logo.png")): ?>
    <a href="index.php">
        <img src="/logo/logo.png" alt="Logo WeatherSky" class="site-logo" />
    </a>
<?php endif; ?>
    <span class="material-symbols-outlined">partly_cloudy_day</span>
    WeatherSky
</h1>

<!-- Formulaire de sélection du mode sombre -->
<form class="display-mode-form">
    <input type="checkbox" id="dark-mode" <?= getCurrentTheme() === 'nuit' ? 'checked' : '' ?> />
    <label for="dark-mode">Mode sombre</label>
</form>

<!-- Section du carrousel d'images météo -->
<div class="carousel">
    <div class="carousel-inner">
        <?php if (empty($images)): ?>
            <p>Aucune image trouvée pour le carrousel.</p>
        <?php else: ?>
            <?php foreach ($images as $image): ?>
                <div class="carousel-item">
                    <img src="<?= htmlspecialchars($photosDir . $image) ?>" alt="Image météo" />
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Script JS : Gestion du mode sombre + carrousel automatique -->
<script>
    // Gestion du mode sombre (checkbox)
    const darkModeToggle = document.getElementById('dark-mode');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('change', function() {
            const theme = document.getElementById('theme');
            if (theme) {
                theme.href = this.checked ? 'css/dark-style.css' : 'css/style.css';
                document.cookie = "theme=" + (this.checked ? "nuit" : "jour") + ";path=/;max-age=" + (30 * 24 * 3600);
            }
        });
    }

    // Carrousel automatique : fait défiler les images toutes les 3 secondes
    const carouselInner = document.querySelector('.carousel-inner');
    const items = document.querySelectorAll('.carousel-item');
    if (items.length > 0) {
        let currentIndex = 0;
        setInterval(() => {
            currentIndex = (currentIndex + 1) % items.length;
            carouselInner.style.transform = `translateX(-${currentIndex * 100}%)`;
        }, 3000);
    }
</script>

<?php
// Inclusion du pied de page HTML
include("include/footer.inc.php");
?>


