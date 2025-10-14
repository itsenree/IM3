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



    // Schlimmste Stunden
    $hourDelayCounts = [];
    // Zähle die Verspätungen pro Stunde
    foreach ($results as $row) {
        $hour = date('H', strtotime($row['ankunftszeit']));

        $obj = array_column($hourDelayCounts, null, 'stunde')[$hour] ?? false;
        if (!$obj) $hourDelayCounts[$hour] = (object) ['stunde' => $hour, 'verspaetungen' => 0, 'datum' => $row['betriebstag']];
        $hourDelayCounts[$hour]->verspaetungen += 1;
    }

    // Finde die schlimmsten Stunden
    usort($hourDelayCounts, function($a, $b)
    {
        return $b->verspaetungen <=> $a->verspaetungen;
    });

    // Ausgabe der schlimmsten Stunden
    $sql = "SELECT * FROM SchlimmsteStundeRekorde";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $recordResults = $stmt->fetchAll();

    foreach ($hourDelayCounts as $record) {
        $alreadyExists = false;
        foreach ($recordResults as $previousRecord) {
            if ((int)$record->stunde === (int)$previousRecord['stunde']) {
                // Wenn die Stunde bereits in den Rekorden vorhanden ist, aktualisiere das Datum
                if ($record->verspaetungen > $previousRecord['verspaetungen']) {
                    $sql = "UPDATE SchlimmsteStundeRekorde SET datum = ? AND verspaetungen = ? WHERE stunde = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$record->datum, $record->verspaetungen, $record->stunde]);
                }

                $alreadyExists = true;
                break;
            }
        }

        if (!$alreadyExists) {
            // Wenn die Stunde noch nicht in den Rekorden ist, füge sie hinzu
            $sql = "INSERT INTO SchlimmsteStundeRekorde (stunde, verspaetungen, datum) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$record->stunde, $record->verspaetungen, $record->datum]);
        }
    }

    // Schlimmste Stunde Award vergeben
    $worstStation = $hourDelayCounts[0];
    echo "\Schlimmste Stunde Award:\n";
    $sql = "SELECT * FROM SchlimmsteStundePreistraeger";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $awardResults = $stmt->fetchAll();
    $exists = false;
    foreach ($awardResults as $award) {
        if ((int)$award['stunde'] === (int)$worstStation->stunde) {
            $exists = true;
            $sql = "UPDATE SchlimmsteStundePreistraeger SET preise = preise + 1 WHERE stunde = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$worstStation->stunde]);
            break;
        }
    }
    if (!$exists) {
        $sql = "INSERT INTO SchlimmsteStundePreistraeger (stunde, preise) VALUES (?, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$worstStation->stunde]);
    }

} catch (PDOException $e) {
    // Gibt eine Fehlermeldung zurück, wenn etwas schiefgeht
    echo json_encode(['error' => $e->getMessage()]);
}