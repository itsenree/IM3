<?php
/* ============================================================================
   HANDLUNGSANWEISUNG (load.php)
   1) Binde 001_config.php (PDO-Config) ein.
   2) Binde transform.php ein → erhalte TRANSFORM-JSON.
   3) json_decode(..., true) → Array mit Datensätzen.
   4) Stelle PDO-Verbindung her (ERRMODE_EXCEPTION, FETCH_ASSOC).
   5) Bereite INSERT/UPSERT-Statement mit Platzhaltern vor.
   6) Iteriere über Datensätze und führe execute(...) je Zeile aus.
   7) Optional: Transaktion verwenden (beginTransaction/commit) für Performance.
   8) Bei Erfolg: knappe Bestätigung ausgeben (oder still bleiben, je nach Kontext).
   9) Bei Fehlern: Exception fangen → generische Fehlermeldung/Code (kein Stacktrace).
  10) Keine Debug-Ausgaben in Produktion; sensible Daten nicht loggen.
   ============================================================================ */


// Transformations-Skript  als '230_transform.php' einbinden
$jsonData = include('transform.php');

// Dekodiert die JSON-Daten zu einem Array
$dataArray = json_decode($jsonData, true);
usort($dataArray, function($a, $b)
{
    return $b['betriebstag'] <=> $a['betriebstag'];
});

require_once '../config.php'; // Bindet die Datenbankkonfiguration ein

try {
    // Erstellt eine neue PDO-Instanz mit der Konfiguration aus config.php
    $pdo = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT * FROM Verspaetungen ORDER BY betriebstag ASC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll();

    echo $dataArray[0]['betriebstag'] . " - " . $results[0]['betriebstag'];
    if ($dataArray[0]['betriebstag'] !== $results[0]['betriebstag']) {
        $sql = "DELETE FROM Verspaetungen";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        // SQL-Query mit Platzhaltern für das Einfügen von Daten
        $sql = "INSERT INTO Verspaetungen (betriebstag, zuglinie, verkehrsmittel, haltestelle, ankunftszeit, an_prognose, ausfall, verspaetung_s) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        // Bereitet die SQL-Anweisung vor
        $stmt = $pdo->prepare($sql);

        // Fügt jedes Element im Array in die Datenbank ein
        foreach ($dataArray as $item) {
            $stmt->execute([
                $item['betriebstag'],
                $item['zuglinie'],
                $item['verkehrsmittel'],
                $item['haltestelle'],
                $item['ankunftszeit'],
                $item['an_prognose'],
                $item['ausfall'],
                ($item['an_prognose'] && $item['ankunftszeit']) ? (date(strtotime($item['an_prognose'])) - date(strtotime($item['ankunftszeit']))) : null
            ]);
        }

        echo "Daten erfolgreich eingefügt.";

        include('awards/ausfalllastigste_haltestelle.php');
        include('awards/längste_verspätung.php');
        include('awards/schlimmste_haltestelle.php');
        include('awards/schlimmste_stunde.php');
        include('awards/unzuverlässigste_zuglinie.php');
        include('awards/unzuverlässigstes_verkehrsmittel.php');

        echo "Awards erfolgreich aktualisiert.";
    }
} catch (PDOException $e) {
    die("Verbindung zur Datenbank konnte nicht hergestellt werden: " . $e->getMessage());
}
