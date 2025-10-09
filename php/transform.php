<?php
// Bindet das Skript extract.php für Rohdaten ein
$data = include('extract.php');

// Initialisiert ein Array, um die transformierten Daten zu speichern
$transformedData = [];
$totalDelays = $data['total_count'];

// Transformiert und fügt die notwendigen Informationen hinzu
foreach ($data['results'] as $delay) {
    // Konstruiert die neue Struktur mit allen angegebenen Feldern, einschließlich des neuen 'condition'-Feldes
    $transformedData[] = [
        'betriebstag' => $delay['betriebstag'],
        'zuglinie' => $delay['linien_text'],
        'verkehrsmittel' => $delay['verkehrsmittel_text'],
        'haltestelle' => $delay['haltestellen_name'],
        'ankunftszeit' => $delay['ankunftszeit'],
        'an_prognose' => $delay['an_prognose'],
        'ausfall' => $delay['faellt_aus_tf'] == "true" ? 1 : 0
    ];
}

// Kodiert die transformierten Daten in JSON
$jsonData = json_encode($transformedData, JSON_PRETTY_PRINT);

// Gibt die JSON-Daten zurück, anstatt sie auszugeben
return $jsonData;