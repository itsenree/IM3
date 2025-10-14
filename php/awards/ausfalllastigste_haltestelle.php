<?php
// Datenbankkonfiguration einbinden
require_once '../config.php';

// Header setzen, um JSON-Inhaltstyp zurückzugeben
header('Content-Type: application/json');

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    $sql = "SELECT * FROM Verspaetungen WHERE ausfall = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll();



    // Unzuverlässigste Haltestellen
    $stationCancellationCounts = [];
    // Zähle die Ausfälle pro Haltestelle
    foreach ($results as $row) {
        $obj = array_column($stationCancellationCounts, null, 'haltestelle')[$row['haltestelle']] ?? false;
        if (!$obj) $stationCancellationCounts[$row['haltestelle']] = (object) ['haltestelle' => $row['haltestelle'], 'ausfaelle' => 0, 'datum' => $row['betriebstag']];
        $stationCancellationCounts[$row['haltestelle']]->ausfaelle += 1;
    }

    // Finde die unzuverlässigsten Haltestellen
    usort($stationCancellationCounts, function($a, $b)
    {
        return $b->ausfaelle <=> $a->ausfaelle;
    });

    // Ausgabe der unzuverlässigsten Haltestellen
    $sql = "SELECT * FROM AusfalllastigsteHaltestelleRekorde";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $recordResults = $stmt->fetchAll();

    foreach ($stationCancellationCounts as $record) {
        $alreadyExists = false;
        foreach ($recordResults as $previousRecord) {
            if ($record->haltestelle === $previousRecord['haltestelle']) {
                // Wenn die Haltestelle bereits in den Rekorden vorhanden ist, aktualisiere das Datum
                if ($record->ausfaelle > $previousRecord['ausfaelle']) {
                    $sql = "UPDATE AusfalllastigsteHaltestelleRekorde SET datum = ? AND ausfaelle = ? WHERE haltestelle = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$record->datum, $record->ausfaelle, $record->haltestelle]);
                }

                $alreadyExists = true;
                break;
            }
        }

        if (!$alreadyExists) {
            // Wenn die Haltestelle noch nicht in den Rekorden ist, füge sie hinzu
            $sql = "INSERT INTO AusfalllastigsteHaltestelleRekorde (haltestelle, ausfaelle, datum) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$record->haltestelle, $record->ausfaelle, $record->datum]);
        }
    }

    // Unzuverlässigste Haltestelle Award vergeben
    $mostCancelledStation = $stationCancellationCounts[0];
    echo "\nUnzuverlässigste Haltestelle Award:\n";
    $sql = "SELECT * FROM AusfalllastigsteHaltestellePreistraeger";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $awardResults = $stmt->fetchAll();
    $exists = false;
    foreach ($awardResults as $award) {
        if ($award['haltestelle'] === $mostCancelledStation->haltestelle) {
            $exists = true;
            $sql = "UPDATE AusfalllastigsteHaltestellePreistraeger SET preise = preise + 1 WHERE haltestelle = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$mostCancelledStation->haltestelle]);
            break;
        }
    }
    if (!$exists) {
        $sql = "INSERT INTO AusfalllastigsteHaltestellePreistraeger (haltestelle, preise) VALUES (?, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$mostCancelledStation->haltestelle]);
    }

} catch (PDOException $e) {
    // Gibt eine Fehlermeldung zurück, wenn etwas schiefgeht
    echo json_encode(['error' => $e->getMessage()]);
}