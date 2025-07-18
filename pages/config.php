<?php

try {
    // Variables d'environnement
    $host = getenv("DB_HOST");
    $port = getenv("DB_PORT");
    $dbname = getenv("DB_NAME");
    $user = getenv("DB_USER");
    $pass = getenv("DB_PASS");

    // Connexion PDO PostgreSQL
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifie si la table "events" existe déjà
    $stmt = $pdo->query("SELECT to_regclass('public.events') AS exists;");
    $tableExists = $stmt->fetchColumn();

    if (!$tableExists) {
        // La table n'existe pas, on initialise la BDD
        $sql = file_get_contents(__DIR__ . '/../esports_db.sql');
        $pdo->exec($sql);
        echo "✅ Base de données initialisée avec succès.";
    } else {
        // Table déjà existante
        // echo "ℹ️ La base de données est déjà initialisée.";
    }

} catch (PDOException $e) {
    echo "❌ Erreur lors de l'initialisation : " . $e->getMessage();
    exit;
}