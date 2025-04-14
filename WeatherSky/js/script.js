document.addEventListener("DOMContentLoaded", async () => {
    console.log("\ud83d\udce6 script.js charg\u00e9");

    const depSelect = document.getElementById("departement");
    const villeDep = document.getElementById("ville-dep");
    const latDep = document.getElementById("lat-dep");
    const lonDep = document.getElementById("lon-dep");

    const inputVille = document.getElementById("ville-ville");
    const suggestions = document.getElementById("suggestions");
    const latVille = document.getElementById("lat-ville");
    const lonVille = document.getElementById("lon-ville");

    let map;
    let marker;

    async function afficherMeteoSurCarte(nom, lat, lon) {
        try {
            const meteoURL = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true&timezone=Europe%2FParis&language=fr`;
            const res = await fetch(meteoURL);
            const data = await res.json();

            const { temperature, windspeed, weathercode } = data.current_weather;

            const weatherIcons = {
                0: "\u2600\ufe0f", 1: "\ud83c\udf24\ufe0f", 2: "\u26c5", 3: "\u2601\ufe0f", 45: "\ud83c\udf2b\ufe0f",
                51: "\ud83c\udf26\ufe0f", 61: "\ud83c\udf27\ufe0f", 71: "\u2744\ufe0f", 95: "\u26c8\ufe0f"
            };
            const icon = weatherIcons[weathercode] || "\u2753";

            const popupText = `
                <strong>${nom}</strong><br>
                ${icon} Temp\u00e9rature : ${temperature}\u00b0C<br>
                \ud83d\udca8 Vent : ${windspeed} km/h
            `;

            if (map !== undefined && map._container) {
                map.remove();
                document.getElementById("map").innerHTML = "";
            }

            map = L.map("map").setView([lat, lon], 8);
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution: "© OpenStreetMap contributors"
            }).addTo(map);

            marker = L.marker([lat, lon]).addTo(map).bindPopup(popupText).openPopup();

            await afficherPrevisions(lat, lon, nom);
        } catch (err) {
            console.error("\u274c Erreur m\u00e9t\u00e9o actuelle :", err);
        }
    }
    window.afficherMeteoSurCarte = afficherMeteoSurCarte;

    async function afficherPrevisions(lat, lon, nom) {
        try {
            const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&daily=temperature_2m_min,temperature_2m_max,weathercode&timezone=Europe%2FParis&language=fr`;
            const res = await fetch(url);
            const data = await res.json();

            const jours = data.daily.time;
            const tmin = data.daily.temperature_2m_min;
            const tmax = data.daily.temperature_2m_max;
            const codes = data.daily.weathercode;

            const container = document.getElementById("previsions");
            container.innerHTML = `<h2>\ud83d\udcc6 Pr\u00e9visions \u00e0 ${nom}</h2><div class="previsions-wrap"></div>`;
            const wrap = container.querySelector(".previsions-wrap");

            const icons = {
                0: "\u2600\ufe0f", 1: "\ud83c\udf24\ufe0f", 2: "\u26c5", 3: "\u2601\ufe0f", 45: "\ud83c\udf2b\ufe0f",
                51: "\ud83c\udf26\ufe0f", 61: "\ud83c\udf27\ufe0f", 71: "\u2744\ufe0f", 95: "\u26c8\ufe0f"
            };

            const joursFr = ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'];
            const moisFr = ['janvier','f\u00e9vrier','mars','avril','mai','juin','juillet','ao\u00fbt','septembre','octobre','novembre','d\u00e9cembre'];

            for (let i = 0; i <= 4; i++) {
                const date = new Date(jours[i]);
                const jourNom = joursFr[date.getDay()];
                const jour = `${jourNom} ${date.getDate()} ${moisFr[date.getMonth()]}`;

                wrap.innerHTML += `
                    <div class="day-card">
                        <h3>${jour}</h3>
                        <p>${icons[codes[i]] || "\u2753"}</p>
                        <p>\ud83c\udf21\ufe0f ${tmin[i]}\u00b0 / ${tmax[i]}\u00b0</p>
                    </div>
                `;
            }
        } catch (err) {
            console.error("\u274c Erreur pr\u00e9visions :", err);
        }
    }

    try {
        const res = await fetch("https://geo.api.gouv.fr/departements?fields=nom,code,centre");
        const departements = await res.json();

        for (const dep of departements) {
            const opt = document.createElement("option");
            opt.textContent = `${dep.code} - ${dep.nom}`;

            if (dep.centre && dep.centre.coordinates) {
                opt.value = JSON.stringify({
                    lat: dep.centre.coordinates[1],
                    lon: dep.centre.coordinates[0],
                    nom: dep.nom
                });
            } else {
                const communeRes = await fetch(`https://geo.api.gouv.fr/departements/${dep.code}/communes?fields=centre&limit=1`);
                const communes = await communeRes.json();
                if (communes.length > 0 && communes[0].centre) {
                    opt.value = JSON.stringify({
                        lat: communes[0].centre.coordinates[1],
                        lon: communes[0].centre.coordinates[0],
                        nom: communes[0].nom
                    });
                } else {
                    continue;
                }
            }
            depSelect.appendChild(opt);
        }

        depSelect.addEventListener("change", async () => {
            const selected = JSON.parse(depSelect.value);
            villeDep.value = selected.nom;
            latDep.value = selected.lat;
            lonDep.value = selected.lon;
            await afficherMeteoSurCarte(selected.nom, selected.lat, selected.lon);
        });

    } catch (err) {
        console.error("\u274c Erreur chargement d\u00e9partements :", err);
    }

    inputVille.addEventListener("input", async () => {
        const nom = inputVille.value.trim();
        if (nom.length < 2) {
            suggestions.innerHTML = "";
            return;
        }

        try {
            const res = await fetch(`https://geo.api.gouv.fr/communes?nom=${nom}&fields=centre&boost=population&limit=5`);
            const villes = await res.json();

            suggestions.innerHTML = "";
            villes.forEach(ville => {
                const li = document.createElement("li");
                li.textContent = `${ville.nom} (${ville.code})`;
                li.addEventListener("click", () => {
                    if (ville.centre && ville.centre.coordinates) {
                        inputVille.value = ville.nom;
                        latVille.value = ville.centre.coordinates[1];
                        lonVille.value = ville.centre.coordinates[0];
                        suggestions.innerHTML = "";

                        afficherMeteoSurCarte(ville.nom, ville.centre.coordinates[1], ville.centre.coordinates[0]);
                    }
                });
                suggestions.appendChild(li);
            });
        } catch (err) {
            console.error("\u274c Erreur auto-compl\u00e9tion ville :", err);
        }
    });
});