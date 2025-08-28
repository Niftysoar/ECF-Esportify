<?php
session_start();
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../../classes/EventManager.php');

// Vérification organisateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organisateur') {
    header('Location: /');
    exit();
}

$organizer_id = $_SESSION['user_id'];
$eventManager = new EventManager($pdo);

// Récupère les événements créés par cet organisateur
$events = $eventManager->getEventsByOrganizer($organizer_id);
?>

<section class="orga">
    <h1>Mes <span class="highlight">événements</span></h1>

    <a href="/createevent" class="btn btn-highlight">Créer un événement</a>
    <a href="/userregister" class="btn btn-highlight">Gérer les inscriptions</a>

    <?php if (empty($events)): ?>
        <p class="no-event">Aucun événement trouvé.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Statut</th>
                    <th>Paramètres</th>
                    <th>Lancement</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $e): ?>
                    <tr>
                        <td><?= htmlspecialchars($e['title']) ?></td>
                        <td><?= htmlspecialchars($e['start_time']) ?></td>
                        <td><?= htmlspecialchars($e['end_date']) ?></td>
                        <td>
                            <?php
                                if ($e['status'] === 'valide') echo '✔️ Validé';
                                elseif ($e['status'] === 'en_attente') echo '⏳ En attente';
                                else echo '❌ Refusé';
                            ?>
                        </td>
                        <td>
                            <?php if ($e['start_time'] > date('Y-m-d H:i:s')): ?>
                                <a href="/editevent?id=<?= $e['id'] ?>" class="btn-edit">Modifier</a>
                            <?php else: ?>
                                <span class="text-muted">Déjà lancé</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($e['status'] === 'valide' && $e['start_time'] > date('Y-m-d H:i:s')): ?>
                                <form method="post" action="/pages/auth/organisateur/launch_event.php">
                                    <input type="hidden" name="event_id" value="<?= $e['id'] ?>">
                                    <button type="submit" class="btn-accept">Lancer</button>
                                </form>
                            <?php else: ?>
                                <em>Indisponible</em>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>