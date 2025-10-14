<?php
// Datenbankkonfiguration einbinden
require_once '../config.php';

// Header setzen, um JSON-Inhaltstyp zurückzugeben
header('Content-Type: application/json');

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    $sql = "SELECT * FROM Verspaetungen WHERE ausfall = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll();



    // Unzuverlässigste Zuglinien
    $lineDelayCounts = [];
    // Zähle die Verspätungen pro Zuglinie
    foreach ($results as $row) {
        $obj = array_column($lineDelayCounts, null, 'zuglinie')[$row['zuglinie']] ?? false;
        if (!$obj) $lineDelayCounts[$row['zuglinie']] = (object) ['zuglinie' => $row['zuglinie'], 'dauer_s' => 0, 'datum' => $row['betriebstag']];
        if ($lineDelayCounts[$row['zuglinie']]->dauer_s < $row['verspaetung_s']) $lineDelayCounts[$row['zuglinie']]->dauer_s = $row['verspaetung_s'];
    }

    usort($lineDelayCounts, function($a, $b)
    {
        return $b->dauer_s <=> $a->dauer_s;
    });

    // Ausgabe der unzuverlässigsten Verkehrsmitteln
    $sql = "SELECT * FROM LaengsteVerspaetungRekorde";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $recordResults = $stmt->fetchAll();
  
    foreach ($lineDelayCounts as $record) {
        $alreadyExists = false;
        foreach ($recordResults as $previousRecord) {
            if ($record->zuglinie === $previousRecord['zuglinie']) {
                // Wenn die Zuglinie bereits in den Rekorden vorhanden ist, aktualisiere das Datum
                if ($record->dauer_s > $previousRecord['dauer_s']) {
                    $sql = "UPDATE LaengsteVerspaetungRekorde SET datum = ? AND dauer_s = ? WHERE zuglinie = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$record->datum, $record->dauer_s, $record->zuglinie]);
                }

                $alreadyExists = true;
                break;
            }
        }
        if (!$alreadyExists) {
            // Wenn die Zuglinie noch nicht in den Rekorden ist, füge sie hinzu
            $sql = "INSERT INTO LaengsteVerspaetungRekorde (zuglinie, dauer_s, datum) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$record->zuglinie, $record->dauer_s, $record->datum]);
        }
    }

    // Längste Verspätung Award vergeben
    $mostDelayedLine = $lineDelayCounts[0];
    echo "\nLängste Verspätung Award:\n";

    $sql = "SELECT * FROM LaengsteVerspaetungPreistraeger";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $awardResults = $stmt->fetchAll();
    $exists = false;
    echo $exists;
    foreach ($awardResults as $award) {
        if ($award['zuglinie'] === $mostDelayedLine->zuglinie) {
            $exists = true;
            $sql = "UPDATE LaengsteVerspaetungPreistraeger SET preise = preise + 1 WHERE zuglinie = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$mostDelayedLine->zuglinie]);
            break;
        }
    }
    if (!$exists) {
        $sql = "INSERT INTO LaengsteVerspaetungPreistraeger (zuglinie, preise) VALUES (?, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$mostDelayedLine->zuglinie]);
    }

} catch (PDOException $e) {
    // Gibt eine Fehlermeldung zurück, wenn etwas schiefgeht
    echo json_encode(['error' => $e->getMessage()]);
}