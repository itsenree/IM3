<?php
// Datenbankkonfiguration einbinden
require_once '../config.php';

// Header setzen, um JSON-Inhaltstyp zurückzugeben
header('Content-Type: application/json');

$category = $_GET['category'] ?? '';

$pdo = new PDO($dsn, $username, $password, $options);
$sql = "";

switch ($category) {
    case 'records':
        $sql = "SELECT * FROM `SchlimmsteStundeRekorde` ORDER BY verspaetungen DESC";
        break;
    case 'winners':
        $sql = "SELECT * FROM `SchlimmsteStundePreistraeger` ORDER BY preise DESC";
        break;
    case 'currentByHour':
        $sql = "SELECT SUBSTRING(ankunftszeit, 12, 2) as stunde, COUNT(ID) as verspaetungen FROM Verspaetungen WHERE ausfall = 0 GROUP BY stunde ORDER BY stunde ASC";
        break;
    default:
        $sql = "SELECT SUBSTRING(ankunftszeit, 12, 2) as stunde, COUNT(ID) as verspaetungen FROM Verspaetungen WHERE ausfall = 0 GROUP BY stunde ORDER BY verspaetungen DESC";
        break;
}

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll();

    // Ausgabe der Ergebnisse
    echo json_encode($results);
} catch (PDOException $e) {
    // Gibt eine Fehlermeldung zurück, wenn etwas schiefgeht
    echo json_encode(['error' => $e->getMessage()]);
}