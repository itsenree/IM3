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
    


    // Unzuverlässigste Verkehrsmitteln
    $transportDelayCounts = [];
    // Zähle die Verspätungen pro Verkehrsmittel
    foreach ($results as $row) {
        $obj = array_column($transportDelayCounts, null, 'verkehrsmittel')[$row['verkehrsmittel']] ?? false;
        if (!$obj) $transportDelayCounts[$row['verkehrsmittel']] = (object) ['verkehrsmittel' => $row['verkehrsmittel'], 'verspaetungen' => 0, 'datum' => $row['betriebstag']];
        $transportDelayCounts[$row['verkehrsmittel']]->verspaetungen += 1;
    }

    // Finde die unzuverlässigsten Verkehrsmitteln
    usort($transportDelayCounts, function($a, $b)
    {
        return $b->verspaetungen <=> $a->verspaetungen;
    });

    // Ausgabe der unzuverlässigsten Verkehrsmitteln
    $sql = "SELECT * FROM UnzuverlaessigsteVerkehrsmittelRekorde";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $recordResults = $stmt->fetchAll();

    foreach ($transportDelayCounts as $record) {
        $alreadyExists = false;
        foreach ($recordResults as $previousRecord) {
            if ($record->verkehrsmittel === $previousRecord['verkehrsmittel']) {
                // Wenn die Zuglinie bereits in den Rekorden vorhanden ist, aktualisiere das Datum
                if ($record->verspaetungen > $previousRecord['verspaetungen']) {
                    $sql = "UPDATE UnzuverlaessigsteVerkehrsmittelRekorde SET datum = ? AND verspaetungen = ? WHERE verkehrsmittel = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$record->datum, $record->verspaetungen, $record->verkehrsmittel]);
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

    

    // Unzuverlässigste Verkehrsmittel Award vergeben
    $mosttransportDelayCounts = $transportDelayCounts[0];
    echo "\nUnzuverlässigste Verkehrsmitteli Award:\n";

    $sql = "SELECT * FROM UnzuverlaessigsteVerkehrsmittelPreistraeger";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $awardResults = $stmt->fetchAll();
    $exists = false;
    echo $exists;
    foreach ($awardResults as $award) {
        if ($award['verkehrsmittel'] === $mosttransportDelayCounts->verkehrsmittel) {
            $exists = true;
            $sql = "UPDATE UnzuverlaessigsteVerkehrsmittelPreistraeger SET preise = preise + 1 WHERE verkehrsmittel = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$mosttransportDelayCounts->verkehrsmittel]);
            break;
        }
    }
    if (!$exists) {
        $sql = "INSERT INTO UnzuverlaessigsteVerkehrsmittelPreistraeger (verkehrsmittel, preise) VALUES (?, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$mosttransportDelayCounts->verkehrsmittel]);
    }

} catch (PDOException $e) {
    // Gibt eine Fehlermeldung zurück, wenn etwas schiefgeht
    echo json_encode(['error' => $e->getMessage()]);
}