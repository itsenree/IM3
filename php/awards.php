<?php
// Datenbankkonfiguration einbinden
require_once '../config.php';

// Header setzen, um JSON-Inhaltstyp zurückzugeben
header('Content-Type: application/json');

try {
    // Erstellt eine neue PDO-Instanz mit der Konfiguration aus config.php
    $pdo = new PDO($dsn, $username, $password, $options);

    // SQL-Query, um Daten basierend auf dem Standort auszuwählen, sortiert nach Zeitstempel
    // Verwende ein Fragezeichen (?) anstelle eines benannten Parameters
    $sql = "SELECT * FROM Verspaetungen WHERE ausfall = 0";

    // Bereitet die SQL-Anweisung vor
    $stmt = $pdo->prepare($sql);

    // Führt die Abfrage mit der Standortvariablen aus, die in einem Array übergeben wird
    // Die Standortvariable ersetzt das erste Fragezeichen in der SQL-Anweisung
    $stmt->execute();

    // Holt alle passenden Einträge
    $results = $stmt->fetchAll();

    // Gibt die Ergebnisse im JSON-Format zurück
    //echo json_encode($results);

    // Unzuverlässigste Zuglinien
    $lineDelayCounts = [];
    foreach ($results as $row) {

        // Zähle die Verspätungen pro Zuglinie
        $line = $row['zuglinie'];
        if (!isset($lineDelayCounts[$line])) {
            $lineDelayCounts[$line] = 0;
        }
        $lineDelayCounts[$line] += 1;
    }

    // Finde die unzuverlässigsten Zuglinien
    arsort($lineDelayCounts);
    $unreliableLines = array_slice($lineDelayCounts, 0, 10);

    // Ausgabe der unzuverlässigsten Zuglinien
    echo "Unzuverlässigste Zuglinien:\n";
     $sql = "SELECT * FROM UnzuverlaessigsteZuglinieRekorde";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $recordResults = $stmt->fetchAll();
    foreach ($unreliableLines as $line => $delay) {
        echo $line . ': ' . $delay . " Verspätungen\n";
        $sql = "INSERT INTO `UnzuverlaessigsteZuglinieRekorde` (`zuglinie`, `verspaetungen`, `datum`) VALUES ('$line', '$delay', '{$results[0]['betriebstag']}')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }

} catch (PDOException $e) {
    // Gibt eine Fehlermeldung zurück, wenn etwas schiefgeht
    echo json_encode(['error' => $e->getMessage()]);
}