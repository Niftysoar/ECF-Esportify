<?php
session_start();
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../../classes/EventManager.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organisateur') {
    header('Location: /');
    exit();
}

$event_id = $_POST['event_id'] ?? null;
$organizer_id = $_SESSION['user_id'];

if (!$event_id) {
    die("ID de l'événement manquant.");
}

try {
    $eventManager = new EventManager($pdo);
    $eventManager->logLaunch($event_id, $organizer_id);
    header('Location: /orga');
    exit();
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}