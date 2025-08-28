<?php
require_once __DIR__ . '/../../mongo.php';

session_start();
require_once(__DIR__ . '/../../config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /');
    exit();
}

$db = mongo_db();
$messages = $db->contact_messages->find([], ['sort' => ['createdAt' => -1]]);
?>

<h1>Messages de contact</h1>

<ul>
  <?php foreach ($messages as $m): ?>
    <li>
      <strong><?= htmlspecialchars($m['name']) ?></strong>
      (<?= htmlspecialchars($m['email']) ?>) :
      <?= nl2br(htmlspecialchars($m['message'])) ?>
    </li>
  <?php endforeach ?>
</ul>