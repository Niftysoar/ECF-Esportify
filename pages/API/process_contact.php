<?php
declare(strict_types=1);

require_once '../mongo.php'; // <-- chemin CORRECT depuis /public/pages

use MongoDB\BSON\UTCDateTime;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['ok'=>false, 'error'=>'Méthode non autorisée']);
  exit;
}

// Récupération brute
$nameRaw    = trim($_POST['name']   ?? '');
$emailRaw   = trim($_POST['email']  ?? '');
$messageRaw = trim($_POST['message']?? '');

// Validations
if ($nameRaw === '' || $emailRaw === '' || $messageRaw === '') {
  http_response_code(400);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['ok'=>false, 'error'=>'Champs requis manquants']); exit;
}
if (!filter_var($emailRaw, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['ok'=>false, 'error'=>'Email invalide']); exit;
}
if (mb_strlen($nameRaw) > 120 || mb_strlen($emailRaw) > 200 || mb_strlen($messageRaw) > 5000) {
  http_response_code(413);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['ok'=>false, 'error'=>'Taille excessive']); exit;
}

// Captcha (checkbox du formulaire)
if (!isset($_POST['captcha']) && !isset($_POST['g-recaptcha-response'])) {
  // Décommentez pour rendre obligatoire :
  // http_response_code(400);
  // header('Content-Type: application/json; charset=utf-8');
  // echo json_encode(['ok'=>false,'error'=>'Captcha requis']); exit;
}

// Échappement pour affichage futur
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
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['ok'=>true, 'message'=>'Votre message a été enregistré.']); exit;
} catch (\Throwable $e) {
  http_response_code(500);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['ok'=>false, 'error'=>'Erreur serveur']); exit;
}

?>