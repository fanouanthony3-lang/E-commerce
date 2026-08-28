<?php
require_once __DIR__ . '/../config/database.php';

// Exemple de requête
$query = $pdo->query("SELECT * FROM produits");
$products = $query->fetchAll();