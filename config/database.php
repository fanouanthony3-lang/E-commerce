<?php
// config/db.php

$host = 'localhost';
$dbname = 'ecommerce_db';
$user = 'root';
$password = '';

try {
    $dsn = "mysql:host=" . $host . ";dbname=" . $dbname . ";charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
