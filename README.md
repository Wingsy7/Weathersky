# WeatherSky

![Status](https://img.shields.io/badge/status-active-success)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat&logo=javascript&logoColor=black)
![Leaflet](https://img.shields.io/badge/Leaflet-199900?style=flat&logo=leaflet&logoColor=white)
![Chart.js](https://img.shields.io/badge/Chart.js-FF6384?style=flat&logo=chartdotjs&logoColor=white)

WeatherSky is a weather-focused web application built around French cities, departments and regions.
It combines PHP rendering, client-side interactions and geographic datasets to display current weather conditions and 5-day forecasts in a practical interface.

## Key Features

- Search by city, department or region
- Interactive map powered by Leaflet
- 5-day forecast with general and detailed views
- Weather statistics and local usage tracking
- Integration of geographic CSV and JSON datasets
- Hybrid PHP and JavaScript workflow

## Tech Stack

| Technology | Usage |
|---|---|
| PHP | Server-side rendering and logic |
| HTML / CSS / JavaScript | Front-end interface |
| Leaflet.js | Interactive map |
| Chart.js | Data visualization |
| Open-Meteo API | Weather data |
| CSV / JSON datasets | Local geographic data |

## Run Locally

```bash
# Clone the repository
git clone https://github.com/Wingsy7/Weathersky.git
cd Weathersky

# Serve the project with a local PHP environment
# Example: XAMPP / Laragon / built-in PHP server depending on your setup
php -S localhost:8000
```

Then open the main application in your browser.

## Repository Structure

```text
Weathersky/
├── WeatherSky/
│   ├── index.php
│   ├── map.php
│   ├── stats.php
│   ├── include/
│   ├── js/
│   ├── css/
│   └── data/
└── README.md
```

## What This Project Demonstrates

- External API integration in a usable web interface
- Practical handling of local datasets
- Front-end interactivity with mapping and charts
- A full web project mixing PHP and JavaScript cleanly

## Author

**Miroslav**  
GitHub: [Wingsy7](https://github.com/Wingsy7)
