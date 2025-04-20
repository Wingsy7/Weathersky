document.addEventListener("DOMContentLoaded", async () => {
  console.log("📦 script.js chargé");

  const depSelect = document.getElementById("departement");
  const inputVille = document.getElementById("ville-ville");
  const suggestions = document.getElementById("suggestions");
  const container = document.getElementById("previsions");

  let villes = [];
  let departements = [];

  try {
    const villesRes = await fetch("data/villes.json");
    const depRes = await fetch("data/departements.json");
    villes = await villesRes.json();
    departements = await depRes.json();
  } catch (e) {
    console.error("❌ Erreur chargement JSON :", e);
    return;
  }

  // === Affichage des départements dans le <select>
  departements.forEach(dep => {
    const opt = document.createElement("option");
    opt.value = dep.DEP;
    opt.textContent = `${dep.DEP} - ${dep.NCCENR}`;
    depSelect.appendChild(opt);
  });

  // === Recherche par département avec debug
  depSelect.addEventListener("change", () => {
    const code = depSelect.value;

    const villesDuDep = villes.filter(v => {
      const cp = String(v.Code_postal).padStart(5, '0');
      return cp.startsWith(code) && v.coordonnees_gps;
    });

    if (villesDuDep.length > 0) {
      const ville = villesDuDep[Math.floor(Math.random() * villesDuDep.length)];
      const [lat, lon] = ville.coordonnees_gps.split(',').map(coord => parseFloat(coord.trim()));

      if (!isNaN(lat) && !isNaN(lon)) {
        const url = `map.php?ville=${encodeURIComponent(ville.Nom_commune)}&lat=${lat}&lon=${lon}`;
        console.log("🧭 Redirection vers :", url);
        window.location.href = url;
      } else {
        console.warn("❌ Coordonnées invalides :", ville);
      }
    } else {
      alert("❌ Aucune ville trouvée dans ce département.");
    }
  });

  // === Auto-complétion des villes
  inputVille.addEventListener("input", () => {
    const nom = inputVille.value.trim().toLowerCase();
    suggestions.innerHTML = "";
    if (nom.length < 2) return;

    const resultats = villes.filter(v =>
      v.Nom_commune && v.Nom_commune.toLowerCase().startsWith(nom)
    ).slice(0, 5);

    resultats.forEach(v => {
      const li = document.createElement("li");
      li.textContent = `${v.Nom_commune} (${v.Code_postal})`;
      li.addEventListener("click", () => {
        const [lat, lon] = v.coordonnees_gps.split(',').map(Number);
        if (!isNaN(lat) && !isNaN(lon)) {
          window.location.href = `map.php?ville=${encodeURIComponent(v.Nom_commune)}&lat=${lat}&lon=${lon}`;
        }
      });
      suggestions.appendChild(li);
    });
  });

  // === Carte interactive Leaflet
  const mapDep = L.map('map-departements').setView([46.5, 2.5], 6);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(mapDep);

  departements.forEach(dep => {
    const villesDuDep = villes.filter(v => {
      const cp = String(v.Code_postal);
      return cp.startsWith(dep.DEP) && v.coordonnees_gps;
    });

    const ville = villesDuDep.length > 0 ? villesDuDep[Math.floor(Math.random() * villesDuDep.length)] : null;

    if (ville) {
      const [lat, lon] = ville.coordonnees_gps.split(',').map(Number);
      if (!isNaN(lat) && !isNaN(lon)) {
        L.marker([lat, lon]).addTo(mapDep)
          .bindPopup(`<strong>${dep.NCCENR}</strong><br><a href="map.php?ville=${encodeURIComponent(ville.Nom_commune)}&lat=${lat}&lon=${lon}">📍 Voir météo</a>`);
      }
    }
  });

  // === Clic libre sur la carte
  mapDep.on("click", async function (e) {
    const lat = e.latlng.lat;
    const lon = e.latlng.lng;

    const popupText = await getMeteoPopup(lat, lon);
    L.marker([lat, lon]).addTo(mapDep).bindPopup(popupText).openPopup();
    await afficherPrevisions(lat, lon, `Coordonnées ${lat.toFixed(2)}, ${lon.toFixed(2)}`);
  });

  async function getMeteoPopup(lat, lon) {
    try {
      const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true&timezone=Europe%2FParis&language=fr`;
      const res = await fetch(url);
      const data = await res.json();

      if (!data.current_weather) throw new Error("Pas de météo actuelle.");

      const { temperature, windspeed, weathercode } = data.current_weather;
      const icons = {
        0: "☀️", 1: "🌤️", 2: "⛅", 3: "☁️", 45: "🌫️",
        51: "🌦️", 61: "🌧️", 71: "❄️", 95: "⛈️"
      };
      const icon = icons[weathercode] || "❓";

      return `
        <strong>📍 Coordonnées</strong><br>
        🌍 ${lat.toFixed(2)}, ${lon.toFixed(2)}<br>
        ${icon} Température : ${temperature}°C<br>
        💨 Vent : ${windspeed} km/h
      `;
    } catch (e) {
      console.error("❌ Erreur météo popup :", e);
      return "Erreur météo.";
    }
  }

  async function afficherPrevisions(lat, lon, nom) {
    try {
      const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&daily=temperature_2m_min,temperature_2m_max,weathercode&timezone=Europe%2FParis&language=fr`;
      const res = await fetch(url);
      const data = await res.json();

      if (!data || !data.daily) throw new Error("Réponse vide de l'API météo");

      const jours = data.daily.time;
      const tmin = data.daily.temperature_2m_min;
      const tmax = data.daily.temperature_2m_max;
      const codes = data.daily.weathercode;

      container.innerHTML = `<h2>📆 Prévisions à ${nom}</h2><div class="previsions-wrap"></div>`;
      const wrap = container.querySelector(".previsions-wrap");

      const icons = {
        0: "☀️", 1: "🌤️", 2: "⛅", 3: "☁️", 45: "🌫️",
        51: "🌦️", 61: "🌧️", 71: "❄️", 95: "⛈️"
      };

      for (let i = 0; i < 5; i++) {
        const date = new Date(jours[i]);
        wrap.innerHTML += `
          <div class="day-card">
            <h3>${date.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long' })}</h3>
            <p>${icons[codes[i]] || "❓"}</p>
            <p>🌡️ ${tmin[i]}° / ${tmax[i]}°</p>
          </div>
        `;
      }
    } catch (err) {
      console.error("❌ Erreur prévisions :", err);
    }
  }

  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has("lat") && urlParams.has("lon")) {
    const nom = urlParams.get("ville") || "Lieu";
    const lat = parseFloat(urlParams.get("lat"));
    const lon = parseFloat(urlParams.get("lon"));
    if (!isNaN(lat) && !isNaN(lon)) {
      await afficherPrevisions(lat, lon, nom);
    }
  }
});
