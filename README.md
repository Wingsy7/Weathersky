# WeatherSky

WeatherSky is a weather-focused web application that displays current conditions and 5-day forecasts for French cities, departments and regions.

The project combines PHP rendering, JavaScript interactions, local geographic datasets and external weather data to build a practical student web app.

## Highlights

- Search weather by city, department or region
- Interactive map powered by Leaflet
- 5-day forecast with general and detailed views
- Local usage statistics stored in JSON files
- PHP includes for shared layout and weather utilities

## Tech Stack

- PHP
- HTML, CSS and JavaScript
- Leaflet.js
- Chart.js
- Open-Meteo API
- CSV and JSON geographic datasets

## Repository Structure

- `WeatherSky/index.php`: main entry page and forecast flow
- `WeatherSky/map.php`: interactive map page
- `WeatherSky/stats.php` and `WeatherSky/statistique.php`: statistics views
- `WeatherSky/include/`: shared PHP components and helpers
- `WeatherSky/js/script.js`: client-side interactions
- `WeatherSky/css/`: application styles
- `WeatherSky/data/`: cities, departments and regions datasets

## What This Project Shows

- Integration of an external API into a usable web interface
- Handling of search, cookies and geolocation fallback in PHP
- Use of mapping and chart libraries to improve UX
- Work with structured local datasets and lightweight analytics

## Notes

The repository contains the current `WeatherSky/` application as well as some older course artifacts kept for reference.
