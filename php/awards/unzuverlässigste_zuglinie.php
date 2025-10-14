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
        if (!$obj) $lineDelayCounts[$row['zuglinie']] = (object) ['zuglinie' => $row['zuglinie'], 'verspaetungen' => 0, 'datum' => $row['betriebstag']];
        $lineDelayCounts[$row['zuglinie']]->verspaetungen += 1;
    }


    // Finde die unzuverlässigsten Zuglinien
    usort($lineDelayCounts, function($a, $b)
    {
        return $b->verspaetungen <=> $a->verspaetungen;
    });

    // Ausgabe der unzuverlässigsten Zuglinien
    $sql = "SELECT * FROM UnzuverlaessigsteZuglinieRekorde";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $recordResults = $stmt->fetchAll();

    foreach ($lineDelayCounts as $record) {
        $alreadyExists = false;
        foreach ($recordResults as $previousRecord) {
            if ($record->zuglinie === $previousRecord['zuglinie']) {
                // Wenn die Zuglinie bereits in den Rekorden vorhanden ist, aktualisiere das Datum
                if ($record->verspaetungen > $previousRecord['verspaetungen']) {
                    $sql = "UPDATE UnzuverlaessigsteZuglinieRekorde SET datum = ? AND verspaetungen = ? WHERE zuglinie = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$record->datum, $record->verspaetungen, $record->zuglinie]);
                }

                $alreadyExists = true;
                break;
            }
        }

        if (!$alreadyExists) {
            // Wenn die Zuglinie noch nicht in den Rekorden ist, füge sie hinzu
            $sql = "INSERT INTO UnzuverlaessigsteZuglinieRekorde (zuglinie, verspaetungen, datum) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$record->zuglinie, $record->verspaetungen, $record->datum]);
        }
    }

    // Unzuverlässigste Zuglinie Award vergeben
    $mostUnreliableLine = $lineDelayCounts[0];
    echo "\nUnzuverlässigste Zuglinie Award:\n";
    $sql = "SELECT * FROM UnzuverlaessigsteZugliniePreistraeger";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $awardResults = $stmt->fetchAll();
    $exists = false;
    foreach ($awardResults as $award) {
        if ($award['zuglinie'] === $mostUnreliableLine->zuglinie) {
            $exists = true;
            $sql = "UPDATE UnzuverlaessigsteZugliniePreistraeger SET preise = preise + 1 WHERE zuglinie = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$mostUnreliableLine->zuglinie]);
            break;
        }
    }
    if (!$exists) {
        $sql = "INSERT INTO UnzuverlaessigsteZugliniePreistraeger (zuglinie, preise) VALUES (?, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$mostUnreliableLine->zuglinie]);
    }

} catch (PDOException $e) {
    // Gibt eine Fehlermeldung zurück, wenn etwas schiefgeht
    echo json_encode(['error' => $e->getMessage()]);
}