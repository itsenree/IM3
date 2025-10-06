<?php

require_once 'config.php';

try {
    // Erstellt eine neue PDO-Instanz
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // Behandelt Verbindungsfehler
    die("Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
}