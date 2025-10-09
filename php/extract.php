<?php

/* ============================================================================
   HANDLUNGSANWEISUNG (extract.php)
   1) Lade Konfiguration/Constants (API-URL, Parameter, ggf. Zeitzone).
   2) Baue die Request-URL (Query-Params sauber via http_build_query).
   3) Initialisiere cURL (curl_init) mit der Ziel-URL.
   4) Setze cURL-Optionen (RETURNTRANSFER, TIMEOUT, HTTP-Header, FOLLOWLOCATION).
   5) Führe Request aus (curl_exec) und prüfe Transportfehler (curl_error).
   6) Prüfe HTTP-Status & Content-Type (JSON erwartet), sonst früh abbrechen.
   7) Dekodiere JSON robust (json_decode(..., true)).
   8) Normalisiere/prüfe Felder (defensive Defaults, Typen casten).
   9) Gib die Rohdaten als PHP-Array ZURÜCK (kein echo) für den Transform-Schritt.
  10) Fehlerfälle: Exception/Fehlerobjekt nach oben reichen (kein HTML ausgeben).
   ============================================================================ */

function fetchDelayData()
{
    $offset = 0;
    $url = "https://data.sbb.ch/api/explore/v2.1/catalog/datasets/ist-daten-sbb/records?limit=100&refine=ankunftsverspatung%3A%22true%22";

    // Initialisiert eine cURL-Sitzung
    $ch = curl_init($url);

    // Setzt Optionen
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Führt die cURL-Sitzung aus und erhält den Inhalt
    $response = curl_exec($ch);

    // Schließt die cURL-Sitzung
    curl_close($ch);

    // Dekodiert die JSON-Antwort und gibt Daten zurück
    $data = json_decode($response, true);
    $totalCount = $data['total_count'];

    while (count($data['results']) < $totalCount) {
        $offset += 100;
        $urlWithOffset = $url . "&offset=" . $offset;

        // Initialisiert eine cURL-Sitzung
        $ch = curl_init($urlWithOffset);

        // Setzt Optionen
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Führt die cURL-Sitzung aus und erhält den Inhalt
        $response = curl_exec($ch);

        // Schließt die cURL-Sitzung
        curl_close($ch);

        // Dekodiert die JSON-Antwort und fügt die neuen Datensätze hinzu
        $newData = json_decode($response, true);
        $data['results'] = array_merge($data['results'], $newData['results']);
    }

    echo "Gesamtanzahl der Verspätungen: " . count($data['results']) . "\n";

    return $data;
}


function fetchCancellationData()
{
    $offset = 0;
    $url = "https://data.sbb.ch/api/explore/v2.1/catalog/datasets/ist-daten-sbb/records?limit=100&refine=faellt_aus_tf%3A%22true%22";

    // Initialisiert eine cURL-Sitzung
    $ch = curl_init($url);

    // Setzt Optionen
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Führt die cURL-Sitzung aus und erhält den Inhalt
    $response = curl_exec($ch);

    // Schließt die cURL-Sitzung
    curl_close($ch);

    // Dekodiert die JSON-Antwort und gibt Daten zurück
    $data = json_decode($response, true);
    $totalCount = $data['total_count'];

    while (count($data['results']) < $totalCount) {
        $offset += 100;
        $urlWithOffset = $url . "&offset=" . $offset;

        // Initialisiert eine cURL-Sitzung
        $ch = curl_init($urlWithOffset);

        // Setzt Optionen
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Führt die cURL-Sitzung aus und erhält den Inhalt
        $response = curl_exec($ch);

        // Schließt die cURL-Sitzung
        curl_close($ch);

        // Dekodiert die JSON-Antwort und fügt die neuen Datensätze hinzu
        $newData = json_decode($response, true);
        $data['results'] = array_merge($data['results'], $newData['results']);
    }

    echo "Gesamtanzahl der Ausfälle: " . count($data['results']) . "\n";

    return $data;
}

// Gibt die Daten zurück, wenn dieses Skript eingebunden ist
$delayData = fetchDelayData();
$cancellationData = fetchCancellationData();
return [
    'total_count' => $delayData['total_count'] + $cancellationData['total_count'],
    'results' => array_merge($delayData['results'], $cancellationData['results'])
];