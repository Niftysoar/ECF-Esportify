<?php
session_start();
require_once(__DIR__ . '/../../config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organisateur') {
    header('Location: /');
    exit();
}

$event_id = $_POST['event_id'] ?? null;
$organizer_id = $_SESSION['user_id'];

if (!$event_id) {
    die("Missing event ID.");
}

// Opzionale: possiamo aggiungere una colonna `is_started` nel DB, oppure scrivere un log.
// Per ora, scriviamo un log semplice nel file.
$log = "[" . date('Y-m-d H:i:s') . "] ID Event $event_id launched by user $organizer_id\n";
file_put_contents('../event_log.txt', $log, FILE_APPEND);

// Torna alla lista eventi
header('Location: my_events.php?launch=ok');
exit();