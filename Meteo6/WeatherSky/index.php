<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



// Active les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Enregistrement de la visite
$ip = $_SERVER['REMOTE_ADDR'];
$data = @json_decode(file_get_contents("http://ip-api.com/json/$ip"), true); // @ pour éviter warning si API ne répond pas

$ville = $data['city'] ?? 'Inconnue';
$pays = $data['country'] ?? 'Inconnu';

$file = 'visites.json';
$visites = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

$visites[] = [
    'ip' => $ip,
    'ville' => $ville,
    'pays' => $pays,
    'date' => date('Y-m-d H:i:s')
];

file_put_contents($file, json_encode($visites, JSON_PRETTY_PRINT));


if (!file_exists("include/header.inc.php")) {
    die("Erreur : include/header.inc.php introuvable");
}
include("include/header.inc.php");
echo en_tete("Accueil"); // ✅ Ajout de l'en-tête complet
echo "<!-- Header inclus -->";

// Vérifier les chemins
$photosDir = 'photo/';
if (!is_dir($photosDir)) {
    error_log("Dossier $photosDir introuvable");
}

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
error_log("Images trouvées : " . count($images));

$randomImage = !empty($images) ? $images[array_rand($images)] : null;
?>

<h1 class="site-title">
  
<?php if (file_exists("logo/logo.png")): ?>
    <a href="index.php">
        <img src="/logo/logo.png" alt="Logo WeatherSky" class="site-logo">
    </a>
<?php else: ?>
    <?php error_log("Logo photo/logo.png introuvable"); ?>
<?php endif; ?>

    <!-- Icône météo juste avant le texte -->
    <span class="material-symbols-outlined">partly_cloudy_day</span>
    WeatherSky
</h1>

    <!-- Formulaire de mode sombre -->
    <form class="display-mode-form">
        <input type="checkbox" id="dark-mode" <?= getCurrentTheme() === 'nuit' ? 'checked' : '' ?>>
        <label for="dark-mode">Mode sombre</label>
    </form>

    <!-- Carousel dynamique avec PHP -->
    <div class="carousel">
        <div class="carousel-inner">
            <?php if (empty($images)): ?>
                <p>Aucune image trouvée pour le carrousel.</p>
            <?php else: ?>
                <?php foreach ($images as $image): ?>
                    <div class="carousel-item">
                        <img src="<?= htmlspecialchars($photosDir . $image) ?>" alt="Image météo">
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

   
</main>

<script>
    // Mode sombre
    const darkModeToggle = document.getElementById('dark-mode');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('change', function() {
            const theme = document.getElementById('theme');
            if (theme) {
                theme.href = this.checked ? 'css/dark-style.css' : 'css/style.css';
                document.cookie = "theme=" + (this.checked ? "nuit" : "jour") + ";path=/;max-age=" + (30 * 24 * 3600);
            } else {
                console.error("Élément #theme introuvable");
            }
        });
    } else {
        console.error("Élément #dark-mode introuvable");
    }

    // Carrousel automatique
    const carouselInner = document.querySelector('.carousel-inner');
    const items = document.querySelectorAll('.carousel-item');
    console.log("Nombre d'éléments du carrousel trouvés :", items.length);
    if (items.length > 0) {
        let currentIndex = 0;
        setInterval(() => {
            console.log("Changement d'image, index :", currentIndex);
            currentIndex = (currentIndex + 1) % items.length;
            carouselInner.style.transform = `translateX(-${currentIndex * 100}%)`;
        }, 3000);
    } else {
        console.error("Aucun élément .carousel-item trouvé");
    }
</script>

<?php include("include/footer.inc.php"); ?>
