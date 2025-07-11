<?php

try {
    $pdo = new PDO(
        "pgsql:host=" . getenv("DB_HOST") .
        ";port=" . getenv("DB_PORT") .
        ";dbname=" . getenv("DB_NAME"),
        getenv("DB_USER"),
        getenv("DB_PASS")
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = file_get_contents(__DIR__ . '/../esports_db.sql');
    $pdo->exec($sql);

    echo "✅ Base de données initialisée avec succès.";
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage();
}