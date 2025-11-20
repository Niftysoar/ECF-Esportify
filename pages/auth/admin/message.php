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

<h1>Messages de <span class="highlight">contact</span></h1>
<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Message</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($messages as $m): ?>
            <tr>
                <td><?= htmlspecialchars($m['name']) ?></td>
                <td><?= htmlspecialchars($m['email']) ?></td>
                <td><?= nl2br(htmlspecialchars($m['message'])) ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>