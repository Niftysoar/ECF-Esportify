<?php
// Inclure le fichier de configuration pour la connexion à la base de données
include('../config.php');

// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: /signin');  // Rediriger si l'utilisateur n'est pas connecté
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

    <section class="dashboard">
        <h1>Mon Tableau <span class="highlight">de Bord</span></h1>

        <div class="container">

            <div class="dashboard-header">
                <a href="/admin" class="btn btn-highlight">ADMIN</a>
            </div>

            <!-- Mes événements -->
            <h2>Mes <span class="highlight">Événements</span></h2>
            <div class="event-list">
                <?php if ($events_user): ?>
                    <?php foreach ($events_user as $event): ?>
                        <div class="event-card">
                            <h3 class="event-title"><?= htmlspecialchars($event['title']); ?></h3>
                            <p class="event-description"><?= htmlspecialchars($event['description']); ?></p>
                            <div class="event-meta">
                                <p><strong>Status :</strong>
                                    <span class="event-status <?= strtolower($event['status']); ?>">
                                        <?= htmlspecialchars($event['status']); ?>
                                    </span>
                                </p>
                                <p><i class="fa-solid fa-user-group"></i> <?= htmlspecialchars($event['player_count']); ?></p>
                                <p><i class="fa-regular fa-calendar"></i> Le <?= date('d/m/Y', strtotime($event['start_time'])); ?> de <?= date('H:i', strtotime($event['start_time'])); ?> à <?= date('H:i', strtotime($event['end_date'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-event">Aucun événement trouvé.</p>
                <?php endif; ?>
            </div>

            <a href="/create" class="btn btn-highlight">Créer un Événement</a>

            <!-- Tous les événements validés -->
            <h2>Tous les Événements <span class="highlight">Validés</span></h2>
            <div class="event-list">
                <?php if ($events_all): ?>
                    <?php foreach ($events_all as $event): ?>
                        <div class="event-card">
                            <h3 class="event-title"><?= htmlspecialchars($event['title']); ?></h3>
                            <p class="event-description"><?= htmlspecialchars($event['description']); ?></p>
                            <div class="event-meta">
                                <p><strong>Organisé par :</strong> <?= htmlspecialchars($event['created_by']); ?></p>
                                <p><i class="fa-solid fa-user-group"></i> <?= htmlspecialchars($event['player_count']); ?></p>
                                <p><i class="fa-regular fa-calendar"></i> Le <?= date('d/m/Y', strtotime($event['start_time'])); ?> de <?= date('H:i', strtotime($event['start_time'])); ?> à <?= date('H:i', strtotime($event['end_date'])); ?></p>
                            </div>
                            <div class="event-actions">
                                <a href="join_event.php?event_id=<?= $event['id'] ?>" class="button">Rejoindre</a>
                                <a href="chat_event.php?event_id=<?= $event['id'] ?>" class="button">Chat</a>
                                <a href="dashboard.php?add_favorite=<?= $event['id']; ?>" class="button">Favoris</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-event">Aucun événement validé.</p>
                <?php endif; ?>
            </div>

            <!-- Favoris -->
            <h2>Événements <span class="highlight">Favoris</span></h2>
            <div class="event-list">
                <?php if ($favorites): ?>
                    <?php foreach ($favorites as $event): ?>
                        <div class="event-card">
                            <h3 class="event-title"><?= htmlspecialchars($event['title']); ?></h3>
                            <p class="event-description"><?= htmlspecialchars($event['description']); ?></p>
                            <div class="event-meta">
                                <p><i class="fa-solid fa-user-group"></i> <?= htmlspecialchars($event['player_count']); ?></p>
                                <p><i class="fa-regular fa-calendar"></i> Le <?= date('d/m/Y', strtotime($event['start_time'])); ?> de <?= date('H:i', strtotime($event['start_time'])); ?> à <?= date('H:i', strtotime($event['end_date'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-event">Aucun événement favori pour le moment.</p>
                <?php endif; ?>
            </div>

            <!-- Scores -->
            <h2>Historique <span class="highlight">des Scores</span></h2>
            <div class="event-list">
                <?php if ($scores): ?>
                    <?php foreach ($scores as $score): ?>
                        <div class="event-card">
                            <h3 class="event-title"><?= htmlspecialchars($score['event_title']); ?></h3>
                            <div class="event-meta">
                                <p><strong>Date :</strong> <?= date('d/m/Y H:i', strtotime($score['start_time'])); ?> - <?= date('H:i', strtotime($score['end_date'])); ?></p>
                                <p><strong>Score :</strong> <?= htmlspecialchars($score['score']); ?></p>
                                <p><strong>Joueur :</strong> <?= htmlspecialchars($score['username']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-event">Aucun score enregistré.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>