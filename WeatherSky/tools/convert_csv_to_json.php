<?php
function csvToJson($csvFile, $jsonFile) {
    $csv = array_map('str_getcsv', file($csvFile));
    $keys = array_map('trim', $csv[0]);
    $data = [];

    for ($i = 1; $i < count($csv); $i++) {
        $row = [];
        foreach ($keys as $j => $key) {
            $row[$key] = $csv[$i][$j] ?? null;
        }
        $data[] = $row;
    }

    file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Tu peux modifier les chemins ici si besoin
csvToJson("../villes.csv", "../villes.json");
csvToJson("../departement.csv", "../departement.json");
csvToJson("../regions.csv", "../regions.json");

echo "✅ Fichiers JSON générés avec succès.";
?>
