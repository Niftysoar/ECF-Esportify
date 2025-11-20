<?php
session_start();
require_once('../config.php');
require_once(__DIR__ . '/../classes/ParticipationManager.php');

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Vous devez être connecté pour rejoindre un événement.";
    header('Location: /lives');
    exit;
}

if (!isset($_GET['event_id'])) {
    $_SESSION['error_message'] = "Aucun événement sélectionné.";
    header('Location: /lives');
    exit;
}

$participationManager = new ParticipationManager($pdo);
$user_id  = (int)$_SESSION['user_id'];
$event_id = (int)$_GET['event_id'];

try {
    $participationManager->joinEvent($user_id, $event_id);
    header("Location: /dashboard");
    exit;
} catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
    header('Location: /lives');
    exit;
}
?>