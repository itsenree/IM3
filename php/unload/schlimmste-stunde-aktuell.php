<?php
// Datenbankkonfiguration einbinden
require_once '../config.php';

// Header setzen, um JSON-Inhaltstyp zurückzugeben
header('Content-Type: application/json');

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    $sql = "SELECT SUBSTRING(ankunftszeit, 12, 2) as zeit, COUNT(ID) FROM Verspaetungen WHERE ausfall = 0 GROUP BY zeit ORDER BY COUNT(ID) DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll();

    // Ausgabe der Ergebnisse
    echo json_encode($results);
} catch (PDOException $e) {
    // Gibt eine Fehlermeldung zurück, wenn etwas schiefgeht
    echo json_encode(['error' => $e->getMessage()]);
}