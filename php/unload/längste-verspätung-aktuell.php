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
        $sql = "SELECT * FROM `LaengsteVerspaetungRekorde` ORDER BY dauer_s DESC";
        break;
    case 'winners':
        $sql = "SELECT * FROM `LaengsteVerspaetungPreistraeger` ORDER BY preise DESC";
        break;
    default:
        $sql = "SELECT zuglinie, verspaetung_s as dauer_s FROM Verspaetungen WHERE ausfall = 0 ORDER BY dauer_s DESC LIMIT 100";
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