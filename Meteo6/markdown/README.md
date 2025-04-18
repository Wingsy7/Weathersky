# 🌦️ Projet : WeatherSky

## 🚀 Technologies utilisées

- HTML, CSS, JavaScript  
- PHP 8  
- JSON, CSV  
- Leaflet.js  
- Chart.js  
- API OpenWeatherMap  
- API NASA (APOD)  
- API GeoPlugin (géolocalisation IP)

---

## 🌐 Contexte du projet

Ce site web interactif a été développé dans le cadre d’un projet scolaire.  
Il permet aux utilisateurs de consulter la météo actuelle et les prévisions sur plusieurs jours, de manière simple, intuitive et personnalisée.

Le projet s’appuie sur :
- Une navigation interactive par carte (Leaflet)
- Des données météo en temps réel (OpenWeatherMap)
- Des statistiques enregistrées côté serveur (CSV/JSON)
- Et des API externes complémentaires (NASA APOD, géolocalisation IP)

---

## 🌍 Accès au site en ligne

🔗 **Site en ligne** : [https://piaki.alwaysdata.net](https://piaki.alwaysdata.net)
🔗 **Site en ligne** : [https://cozma.alwaysdata.net](https://cozma.alwaysdata.net)

---

## 🧠 Fonctionnalités principales

- 🔎 **Recherche météo par ville** (affichage simple ou détaillé)
- 🗺️ **Navigation par carte interactive** :
  - Choix d'une région, d'un département, puis d'une ville
  - Popup météo et bouton pour consulter les prévisions
- ☀️ **Prévisions météo** :
  - Météo du jour + 5 jours
  - Température, humidité, vent, icône météo
- 📊 **Statistiques d’utilisation** :
  - Enregistrement des consultations (villes, départements, clics)
  - Affichage graphique avec Chart.js (`statistique.php`)
- 📸 **Image aléatoire** :
  - À chaque visite de l’accueil (`index.php`), une image météo différente s’affiche
- 🧭 **Géolocalisation approximative** :
  - Détection IP et affichage des données météo (en JSON/XML)
- 🍪 **Personnalisation utilisateur** :
  - Cookie pour la dernière ville consultée
  - Choix du thème (jour/nuit)

---

## 📁 Structure du projet

index.php             → Page d’accueil avec image aléatoire
meteo.php             → Affiche les prévisions météo
statistique.php       → Affiche les graphiques statistiques
includes/             → Fonctions PHP centralisées
data/                 → Fichiers JSON : villes, départements, régions
scripts/              → JS : gestion carte, API, cookies, événements
style.css             → Feuille de style (responsive, sombre/clair)
stats/                → CSV des consultations

---

## 📊 Statistiques

Les interactions des utilisateurs sont automatiquement enregistrées (recherches de villes, départements, clics sur la carte)  
et visualisées sous forme de graphiques dans `statistique.php` grâce à Chart.js.

---

## 🔌 APIs & Librairies utilisées

- [OpenWeatherMap API](https://openweathermap.org/) – météo actuelle et prévisions
- [Leaflet.js](https://leafletjs.com/) – carte interactive
- [Chart.js](https://www.chartjs.org/) – graphiques statistiques
- [NASA APOD API](https://api.nasa.gov/) – image du jour
- [GeoPlugin API](https://www.geoplugin.com/) – géolocalisation IP

---

## 💡 Utilisation

1. Cloner le projet ou copier les fichiers sur un serveur PHP 8+
2. Vérifier que les dossiers `data/` et `stats/` sont accessibles en écriture
3. Ouvrir `index.php` dans un navigateur pour démarrer  
4. Ou visiter directement : [https://piaki.alwaysdata.net](https://piaki.alwaysdata.net)

---

## 👥 Auteurs

Projet réalisé par **[Deborah piaki & COZMA Miroslav]**  
Dans le cadre du cours de **développement web dynamique**.
