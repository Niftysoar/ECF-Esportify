<?php
// Connexion à la base de données
require_once('../config.php');

// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté et a les droits admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: /signin'); // Rediriger vers la page de connexion si l'utilisateur n'est pas un admin
    exit;
}

// Traitement de la validation ou du rejet
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_id'], $_POST['action'])) {
    $event_id = (int) $_POST['event_id'];
    $new_status = ($_POST['action'] === 'accept') ? 'valide' : 'non_valide';

    // Mise à jour du statut de l'événement
    $updateStmt = $pdo->prepare("UPDATE events SET status = :new_status WHERE id = :event_id");
    $updateStmt->execute(['new_status' => $new_status, 'event_id' => $event_id]);

    // Redirection vers la page après mise à jour
    header('Location: /admin');
    exit;
}

// Récupération des événements en attente de validation
$stmt = $pdo->prepare("
    SELECT e.id, e.title, e.start_time, e.end_date, e.player_count, u.username 
    FROM events e 
    JOIN users u ON e.created_by = u.id 
    WHERE e.status = 'en_attente'
");
$stmt->execute();
$pendingEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <section class="admin">

        <h1>
            Événements en <span class="highlight">attente de validation</span>
        </h1>

        <?php if (!empty($pendingEvents)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Date de début</th>
                        <th>Date de fin</th>
                        <th>Nombre de joueurs</th>
                        <th>Créateur</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingEvents as $event): ?>
                        <tr>
                            <td><?= htmlspecialchars($event['title']); ?></td>
                            <td><?= htmlspecialchars($event['start_time']); ?></td>
                            <td><?= htmlspecialchars($event['end_date']); ?></td>
                            <td><?= htmlspecialchars($event['player_count']); ?></td>
                            <td><?= htmlspecialchars($event['username']); ?></td>
                            <td>
                                <form action="/pages/auth/admin_dashboard.php" method="POST" style="display:inline-block;">
                                    <input type="hidden" name="event_id" value="<?= $event['id']; ?>">
                                    <button type="submit" name="action" value="accept" class="btn-accept">Accepter</button>
                                    <button type="submit" name="action" value="reject" class="btn-reject">Refuser</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php else: ?>
            <p style="text-align:center;">Aucun événement en attente de validation.</p>
        <?php endif; ?>
    </section>