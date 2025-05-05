<?php
// Inclure le fichier de configuration pour la connexion à la base de données
include('config.php');

// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');  // Rediriger si l'utilisateur n'est pas connecté
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les événements créés par l'utilisateur et validés
$stmt_events_user = $pdo->prepare("SELECT * FROM events WHERE created_by = ? AND status = 'valide' ORDER BY start_time DESC");
$stmt_events_user->execute([$user_id]);
$events_user = $stmt_events_user->fetchAll();

// Récupérer tous les événements validés (visibles pour tous)
$stmt_events_all = $pdo->prepare("SELECT * FROM events WHERE status = 'valide' ORDER BY start_time DESC");
$stmt_events_all->execute();
$events_all = $stmt_events_all->fetchAll();

// Ajouter un événement aux favoris
if (isset($_GET['add_favorite'])) {
    $event_id = $_GET['add_favorite'];

    // Vérifier si l'événement est déjà dans les favoris
    $stmt_check_favorite = $pdo->prepare("SELECT * FROM favorites WHERE user_id = ? AND event_id = ?");
    $stmt_check_favorite->execute([$user_id, $event_id]);

    if ($stmt_check_favorite->rowCount() == 0) {
        // Ajouter l'événement aux favoris
        $stmt_add_favorite = $pdo->prepare("INSERT INTO favorites (user_id, event_id) VALUES (?, ?)");
        $stmt_add_favorite->execute([$user_id, $event_id]);
    }
}

// Récupérer les événements favoris de l'utilisateur
$stmt_favorites = $pdo->prepare("SELECT events.* 
                                FROM events 
                                JOIN favorites ON events.id = favorites.event_id 
                                WHERE favorites.user_id = ? 
                                ORDER BY events.start_time DESC");
$stmt_favorites->execute([$user_id]);
$favorites = $stmt_favorites->fetchAll();

// Récupérer l'historique des scores de l'utilisateur
$stmt_scores = $pdo->prepare("
    SELECT s.score, e.title AS event_title, e.start_time, e.end_date 
    FROM scores s
    JOIN events e ON s.event_id = e.id
    WHERE s.user_id = ? AND e.end_date < NOW()
    ORDER BY e.end_date DESC
");
$stmt_scores->execute([$user_id]);
$scores = $stmt_scores->fetchAll();

?>

    <h1>Mon Tableau de Bord</h1>

    <div class="container">
        <div class="dashboard-header">
            <!-- Bouton pour créer un nouvel événement -->
            <a href="/create" class="button">Créer un Nouvel Événement</a>
        </div>

        <h2>Mes Événements</h2>

        <?php if ($events_user): ?>
            <?php foreach ($events_user as $event): ?>
                <div class="event-card">
                    <h2><?= htmlspecialchars($event['title']); ?></h2>
                    <p><strong>Description :</strong> <?= htmlspecialchars($event['description']); ?></p>
                    <p><strong>Date de début :</strong> <?= htmlspecialchars($event['start_time']); ?></p>
                    <p><strong>Date de fin :</strong> <?= htmlspecialchars($event['end_date']); ?></p>
                    <p><strong>Nombre de joueurs :</strong> <?= htmlspecialchars($event['player_count']); ?></p>
                    <p><strong>Status :</strong>
                        <span class="event-status <?= strtolower($event['status']); ?>">
                            <?= htmlspecialchars($event['status']); ?>
                        </span>
                    </p>
                    <div class="event-card-footer">
                        <p><strong>Créé par :</strong> <?= htmlspecialchars($event['created_by']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun événement validé trouvé.</p>
        <?php endif; ?>

        <h2>Tous les Événements Validés</h2>

        <?php if ($events_all): ?>
            <?php foreach ($events_all as $event): ?>
                <div class="event-card">
                    <h2><?= htmlspecialchars($event['title']); ?></h2>
                    <p><strong>Description :</strong> <?= htmlspecialchars($event['description']); ?></p>
                    <p><strong>Date de début :</strong> <?= htmlspecialchars($event['start_time']); ?></p>
                    <p><strong>Date de fin :</strong> <?= htmlspecialchars($event['end_date']); ?></p>
                    <p><strong>Nombre de joueurs :</strong> <?= htmlspecialchars($event['player_count']); ?></p>
                    <p><strong>Status :</strong>
                        <span class="event-status <?= strtolower($event['status']); ?>">
                            <?= htmlspecialchars($event['status']); ?>
                        </span>
                    </p>
                    <div class="event-card-footer">
                        <p><strong>Créé par :</strong> <?= htmlspecialchars($event['created_by']); ?></p>
                    </div>
                    <a href="join_event.php?event_id=<?= $event['id'] ?>" class="button">Rejoindre l'événement</a>
                    <a href="chat_event.php?event_id=<?= $event['id'] ?>" class="button">Rejoindre le chat</a>
                    <a href="dashboard.php?add_favorite=<?= $event['id']; ?>" class="button">Mettre en favoris</a>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun événement validé pour le moment.</p>
        <?php endif; ?>
    </div>
    <h2>Événements Favoris</h2>

    <?php if ($favorites): ?>
        <?php foreach ($favorites as $event): ?>
            <div class="event-card">
                <h2><?= htmlspecialchars($event['title']); ?></h2>
                <p><strong>Description :</strong> <?= htmlspecialchars($event['description']); ?></p>
                <p><strong>Date de début :</strong> <?= htmlspecialchars($event['start_time']); ?></p>
                <p><strong>Date de fin :</strong> <?= htmlspecialchars($event['end_date']); ?></p>
                <p><strong>Nombre de joueurs :</strong> <?= htmlspecialchars($event['player_count']); ?></p>
                <p><strong>Status :</strong>
                    <span class="event-status <?= strtolower($event['status']); ?>">
                        <?= htmlspecialchars($event['status']); ?>
                    </span>
                </p>
                <div class="event-card-footer">

                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun événement favori pour le moment.</p>
    <?php endif; ?>


    <h2>Historique des Scores</h2>

    <?php if ($scores): ?>
        <?php foreach ($scores as $score): ?>
            <div class="score-card">
                <h3><?= htmlspecialchars($score['event_title']); ?></h3>
                <p><strong>Date de l'événement :</strong> <?= htmlspecialchars($score['start_time']); ?> - <?= htmlspecialchars($score['end_date']); ?></p>
                <p><strong>Score :</strong> <?= htmlspecialchars($score['score']); ?></p>
                <p><strong>Joueur :</strong> <?= htmlspecialchars($score['username']); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun score enregistré.</p>
    <?php endif; ?>