<?php
declare(strict_types=1);

session_start();
require_once '../mongo.php';

use MongoDB\BSON\UTCDateTime;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error_message'] = "Méthode non autorisée.";
    header("Location: /contact");
    exit;
}

// Récupération brute
$nameRaw    = trim($_POST['name']   ?? '');
$emailRaw   = trim($_POST['email']  ?? '');
$messageRaw = trim($_POST['message']?? '');

// Validations
if ($nameRaw === '' || $emailRaw === '' || $messageRaw === '') {
    $_SESSION['error_message'] = "Tous les champs sont requis.";
    header("Location: /contact");
    exit;
}
if (!filter_var($emailRaw, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = "Email invalide.";
    header("Location: /contact");
    exit;
}
if (mb_strlen($nameRaw) > 120 || mb_strlen($emailRaw) > 200 || mb_strlen($messageRaw) > 5000) {
    $_SESSION['error_message'] = "Taille des champs excessive.";
    header("Location: /contact");
    exit;
}

// Captcha
if (!isset($_POST['captcha'])) {
    $_SESSION['error_message'] = "Captcha requis.";
    header("Location: /contact");
    exit;
}

// Échappement
$name    = htmlspecialchars($nameRaw,    ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$email   = htmlspecialchars($emailRaw,   ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$message = htmlspecialchars($messageRaw, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

$db = mongo_db();
$doc = [
  'name'        => $name,
  'email'       => $email,
  'message'     => $message,
  'messageRaw'  => $messageRaw,
  'ip'          => $_SERVER['REMOTE_ADDR'] ?? null,
  'ua'          => $_SERVER['HTTP_USER_AGENT'] ?? null,
  'createdAt'   => new UTCDateTime(),
];

try {
    $db->contact_messages->insertOne($doc);
    $_SESSION['success_message'] = "Votre message a bien été enregistré 👍";
    header("Location: /contact");
    exit;
} catch (\Throwable $e) {
    $_SESSION['error_message'] = "Erreur serveur, veuillez réessayer.";
    header("Location: /contact");
    exit;
}