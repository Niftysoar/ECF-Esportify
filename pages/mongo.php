<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;
use MongoDB\Database;

function mongo_db(): Database {
    static $db = null;
    if ($db instanceof Database) {
        return $db;
    }

    // 🔑 Utilise vos variables d’environnement si définies
    $uri    = getenv('MONGODB_URI');
    $dbName = getenv('MONGODB_DB');

    $client = new Client($uri);
    $db     = $client->selectDatabase($dbName);

    return $db;
}