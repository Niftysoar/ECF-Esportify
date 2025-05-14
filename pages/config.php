<?php
$host = 'mysql:host=db;port=3306';  // Hôte de la base de données
$dbname = 'esports_db';  // Nom de la base de données
$username = 'root';  // Utilisateur
$password = 'supermotdepasse';  // Mot de passe

try {
    $dsn = $host . ';dbname=' . $dbname;
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}