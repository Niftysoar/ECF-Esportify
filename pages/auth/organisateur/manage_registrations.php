<?php
session_start();
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../../classes/ParticipationManager.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organisateur') {
    header('Location: /');
    exit();
}

$organizer_id = $_SESSION['user_id'];
$manager = new ParticipationManager($pdo);

// Gérer une action POST (accepter ou refuser)
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['user_id'], $_POST['event_id'], $_POST['action'])
) {
    $user_id = (int)$_POST['user_id'];
    $event_id = (int)$_POST['event_id'];
    $status = $_POST['action'] === 'accept' ? 'accepte' : 'refuse';

    $manager->updateStatus($user_id, $event_id, $status, $organizer_id);
}

// Récupération des participations
$registrations = $manager->getRegistrationsByOrganizer($organizer_id);
?>

<section class="container my-5">
    <h1>Gestion des <span class="highlight">participations à vos événements</span></h1>

    <?php if (count($registrations) === 0): ?>
        <div class="alert">Aucune participation enregistrée.</div>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Événement</th>
                    <th>Date</th>
                    <th>Participant</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registrations as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['event_title']) ?></td>
                        <td><?= htmlspecialchars($r['start_time']) ?></td>
                        <td><?= htmlspecialchars($r['player']) ?></td>
                        <td><?= ucfirst($r['status']) ?></td>
                        <td>
                            <?php if ($r['status'] === 'en_attente'): ?>
                                <form action="/pages/auth/organisateur/manage_registrations.php" method="POST" style="display:inline-block;">
                                    <input type="hidden" name="user_id" value="<?= $r['user_id'] ?>">
                                    <input type="hidden" name="event_id" value="<?= $r['event_id'] ?>">
                                    <button name="action" value="accept" class="btn-accept">Accepter</button>
                                    <button name="action" value="reject" class="btn-reject">Refuser</button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="/orga" class="btn btn-highlight">Retour à mes événements</a>
</section>