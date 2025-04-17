# 🌦️ Projet : Météo Interactive Web  
**Technologies utilisées :** HTML, CSS, JavaScript, PHP 8, JSON, Chart.js, Leaflet.js, API Open-Meteo  

## 🌐 Contexte du projet  
Ce projet web a pour but d’afficher les **prévisions météo sur 5 jours**, la **météo actuelle** et diverses **statistiques d’utilisation**, en s’appuyant sur les coordonnées GPS issues de fichiers JSON (villes, départements, régions) et l’API [Open-Meteo](https://open-meteo.com).  
Il permet aussi une **recherche par ville**, une **navigation via une carte interactive**, ainsi que l’affichage dynamique de statistiques (graphiques) et d’infos complémentaires (trafic, news, bourse).  

## 🧠 Fonctionnalités principales

- Recherche météo par **nom de ville** avec autocomplétion.
- Recherche météo par **département** (prend une ville au hasard dans le département).
- Carte interactive : clic sur un point → popup météo + prévisions.
- Affichage des prévisions météo sur **5 jours** (température et icône météo).
- Enregistrement des statistiques (nombre de recherches ville, département, clics carte).
- Page dédiée aux **statistiques** avec **Chart.js**.
- Intégration de modules supplémentaires : **actualités récentes**, **trafic**, **cours de la bourse** *(à venir)*.

## 📁 Structure du projet

- `index.php` : page d’accueil (météo, carte, formulaire).
- `meteo.php` : affiche les prévisions météo détaillées.
- `stats.php` : affiche les statistiques sous forme de graphiques.
- `data/villes.json`, `departements.json`, `regions.json` : fichiers de données locales.
- `script.js` : logique principale côté client (API, carte, événements).
- `style.css` : design responsive et moderne.
- `stats_villes.json`, `stats_departements.json`, `stats_map.json` : fichiers de statistiques.

## 📊 Statistiques

Générées et mises à jour automatiquement selon l'interaction de l'utilisateur. Affichées dans `stats.php` grâce à **Chart.js**.

## 🗺️ APIs & Librairies utilisées

- [Open-Meteo API](https://open-meteo.com) pour météo actuelle et prévisions.
- [Leaflet.js](https://leafletjs.com) pour la carte interactive.
- [Chart.js](https://www.chartjs.org) pour les graphiques statistiques.
- API info/news/trafic/bourse *(en cours d’intégration)*.

## 💡 Utilisation

- Cloner le projet ou copier les fichiers sur un serveur compatible PHP.
- S'assurer que les fichiers JSON sont bien accessibles dans le dossier `data/`.
- Lancer `index.php` depuis un navigateur.

## 👥 Auteurs

Projet réalisé par **[Ton Nom ou Groupe]**,  
dans le cadre du cours/projet de développement web dynamique.
