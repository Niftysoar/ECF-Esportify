<?php
// $host = 'mysql:host=db;port=3306';  // Hôte de la base de données
// $dbname = 'esports_db';  // Nom de la base de données
// $username = 'root';  // Utilisateur
// $password = 'supermotdepasse';  // Mot de passe

$pdo = new PDO(
  "mysql:host=" . getenv("DB_HOST") . ";dbname=" . getenv("DB_NAME") . ";charset=utf8mb4",
  getenv("DB_USER"),
  getenv("DB_PASS")
);

try {
    $dsn = $host . ';dbname=' . $dbname;
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}