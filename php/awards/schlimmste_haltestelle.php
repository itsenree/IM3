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



    // Unzuverlässigste Haltestellen
    $stationDelayCounts = [];
    // Zähle die Verspätungen pro Haltestelle
    foreach ($results as $row) {
        $obj = array_column($stationDelayCounts, null, 'haltestelle')[$row['haltestelle']] ?? false;
        if (!$obj) $stationDelayCounts[$row['haltestelle']] = (object) ['haltestelle' => $row['haltestelle'], 'verspaetungen' => 0, 'datum' => $row['betriebstag']];
        $stationDelayCounts[$row['haltestelle']]->verspaetungen += 1;
    }


    // Finde die unzuverlässigsten Haltestellen
    usort($stationDelayCounts, function($a, $b)
    {
        return $b->verspaetungen <=> $a->verspaetungen;
    });

    // Ausgabe der unzuverlässigsten Haltestellen
    $sql = "SELECT * FROM SchlimmsteHaltestellenRekorde";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $recordResults = $stmt->fetchAll();

    foreach ($stationDelayCounts as $record) {
        $alreadyExists = false;
        foreach ($recordResults as $previousRecord) {
            if ($record->haltestelle === $previousRecord['haltestelle']) {
                // Wenn die Haltestelle bereits in den Rekorden vorhanden ist, aktualisiere das Datum
                if ($record->verspaetungen > $previousRecord['verspaetungen']) {
                    $sql = "UPDATE SchlimmsteHaltestellenRekorde SET datum = ? AND verspaetungen = ? WHERE haltestelle = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$record->datum, $record->verspaetungen, $record->haltestelle]);
                }

                $alreadyExists = true;
                break;
            }
        }

        if (!$alreadyExists) {
            // Wenn die Haltestelle noch nicht in den Rekorden ist, füge sie hinzu
            $sql = "INSERT INTO SchlimmsteHaltestellenRekorde (haltestelle, verspaetungen, datum) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$record->haltestelle, $record->verspaetungen, $record->datum]);
        }
    }

    // Unzuverlässigste Haltestelle Award vergeben
    $worstStation = $stationDelayCounts[0];
    echo "\nUnzuverlässigste Haltestelle Award:\n";
    $sql = "SELECT * FROM SchlimmsteHaltestellenPreistraeger";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $awardResults = $stmt->fetchAll();
    $exists = false;
    foreach ($awardResults as $award) {
        if ($award['haltestelle'] === $worstStation->haltestelle) {
            $exists = true;
            $sql = "UPDATE SchlimmsteHaltestellenPreistraeger SET preise = preise + 1 WHERE haltestelle = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$worstStation->haltestelle]);
            break;
        }
    }
    if (!$exists) {
        $sql = "INSERT INTO SchlimmsteHaltestellenPreistraeger (haltestelle, preise) VALUES (?, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$worstStation->haltestelle]);
    }

} catch (PDOException $e) {
    // Gibt eine Fehlermeldung zurück, wenn etwas schiefgeht
    echo json_encode(['error' => $e->getMessage()]);
}