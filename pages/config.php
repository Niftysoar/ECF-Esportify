<?php
// $host = 'mysql:host=db;port=3306';  // Hôte de la base de données
// $dbname = 'esports_db';  // Nom de la base de données
// $username = 'root';  // Utilisateur
// $password = 'supermotdepasse';  // Mot de passe

// $pdo = new PDO(
//   "mysql:host=" . getenv("DB_HOST") . ";dbname=" . getenv("DB_NAME") . ";charset=utf8mb4",
//   getenv("DB_USER"),
//   getenv("DB_PASS")
// );

// try {
//     $dsn = $host . ';dbname=' . $dbname;
//         $pdo = new PDO($dsn, $username, $password);
//         $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
//         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     die("Erreur de connexion : " . $e->getMessage());
// }

try {
    $host = getenv("DB_HOST"); // Hôte de la base de données
    $port = getenv("DB_PORT");
    $dbname = getenv("DB_NAME"); // Nom de la base de données
    $user = getenv("DB_USER"); // Utilisateur
    $pass = getenv("DB_PASS"); // Mot de passe

    // ✅ Chaîne DSN PostgreSQL bien formée
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Décommenter
try {
    $pdo = new PDO( 
        "pgsql:host=" . getenv("DB_HOST") . // Hôte de la base de données
        ";port=" . getenv("DB_PORT") .
        ";dbname=" . getenv("DB_NAME"), // Nom de la base de données
        getenv("DB_USER"), // Utilisateur
        getenv("DB_PASS") // Mot de passe
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = file_get_contents(__DIR__ . '/../esports_db.sql');
    $pdo->exec($sql);

    echo "✅ Base de données initialisée avec succès.";
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage();
}