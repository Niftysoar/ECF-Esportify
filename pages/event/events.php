<?php

session_start();
require_once('../config.php');

// Initialisation des filtres
$filter_player_count = isset($_GET['player_count']) ? $_GET['player_count'] : '';
$filter_date = isset($_GET['date']) ? $_GET['date'] : '';
$filter_username = isset($_GET['username']) ? $_GET['username'] : '';

// Affichage d'une erreur s’il y en a en session
$error = null;
if (isset($_SESSION['error_message'])) {
    $error = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

try {
    // Construction de la requête avec filtres
    $sql = "SELECT events.*, users.username
            FROM events
            JOIN users ON events.created_by = users.id
            WHERE events.status = 'valide'";

    // Ajout des conditions de filtrage
    if ($filter_player_count) {
        $sql .= " AND events.player_count >= :player_count";
    }
    if ($filter_date) {
        $sql .= " AND DATE(events.start_time) = :date";
    }
    if ($filter_username) {
        $sql .= " AND users.username LIKE :username";
    }

    $sql .= " ORDER BY start_time ASC";

    $stmt = $pdo->prepare($sql);

    // Binding des paramètres pour la requête préparée
    if ($filter_player_count) {
        $stmt->bindParam(':player_count', $filter_player_count, PDO::PARAM_INT);
    }
    if ($filter_date) {
        $stmt->bindParam(':date', $filter_date, PDO::PARAM_STR);
    }
    if ($filter_username) {
        $stmt->bindParam(':username', $filter_username, PDO::PARAM_STR);
    }

    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Si la requête est une requête AJAX, retourner les données en JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($events);
        exit; // Arrêter l'exécution ici pour les requêtes AJAX
    }
} catch (PDOException $e) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    } else {
        die("Erreur lors de la récupération des événements : " . $e->getMessage());
    }
}
?>

    <section class="live-page">

        <h1 class="live-title">Événements E-sport <span class="highlight"><br>à venir</span></h1>

        <!-- Pop-up d'erreur -->
        <?php if ($error): ?>
            <div class="popup-error" id="popupError">
                <p><?= htmlspecialchars($error) ?></p>
                <button class="btn btn-highlight" onclick="document.getElementById('popupError').style.display='none'">Fermer</button>
            </div>
        <?php endif; ?>

        <!-- Formulaire de filtre -->
        <form id="filter-form" class="filter-bar">
            <div class="filter-group">
                <label for="filter-username">Créateur</label>
                <select id="filter-username" name="username">
                    <option value="">Tout</option>
                    <!-- Options dynamiques en PHP si besoin -->
                </select>
            </div>

            <div class="filter-group">
                <label for="filter-date">Date</label>
                <select id="filter-date" name="date">
                    <option value="">Tout</option>
                    <!-- Exemples -->
                    <option value="<?= date('Y-m-d') ?>">Aujourd'hui</option>
                    <option value="<?= date('Y-m-d', strtotime('+1 day')) ?>">Demain</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="filter-player-count">Nombre de joueurs</label>
                <select id="filter-player-count" name="player_count">
                    <option value="">Tout</option>
                    <option value="2">2+</option>
                    <option value="4">4+</option>
                    <option value="8">8+</option>
                </select>
            </div>

            <button type="submit" class="btn btn-highlight">FILTRER</button>
        </form>

        <div class="event-list" id="event-list">
            <?php if (count($events) > 0): ?>
                <?php foreach ($events as $event): ?>
                    <div class="event-card">
                        <h3 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p class="event-description"><?php echo htmlspecialchars($event['description']); ?></p>
                        <div class="event-meta">
                            <p><strong>Organisé par :</strong> <?php echo htmlspecialchars($event['username']); ?></p>
                            <p><strong><i class="fa-solid fa-user-group"></i></strong> <?php echo htmlspecialchars($event['player_count']); ?></p>
                            <p><strong><i class="fa-regular fa-calendar"></i></strong> Le <?php echo date('d/m/Y', strtotime($event['start_time'])); ?> de <?php echo date('H:i', strtotime($event['start_time'])); ?> à <?php echo date('H:i', strtotime($event['end_date'])); ?></p>
                        </div>
                        <div class="event-actions">
                            <a href="/pages/event/join_event.php?event_id=<?= $event['id'] ?>" class="btn btn-highlight">Rejoindre</a>
                            <a href="chat_event.php?event_id=<?= $event['id'] ?>" class="button">Chat</a>
                            <a href="/pages/auth/dashboard.php?add_favorite=<?= $event['id']; ?>" class="button">Favoris</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-event">Aucun événement à afficher pour le moment.</p>
            <?php endif; ?>
        </div>
    </section>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('apply-filter').addEventListener('click', () => {
                    const playerCount = document.getElementById('filter-player-count').value;
                    const date = document.getElementById('filter-date').value;
                    const username = document.getElementById('filter-username').value;

                    const params = new URLSearchParams({
                        player_count: playerCount,
                        date: date,
                        username: username
                    });

                    fetch('events.php?' + params.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        const eventList = document.getElementById('event-list');
                        eventList.innerHTML = '';

                        if (data.length > 0) {
                            data.forEach(event => {
                                const card = document.createElement('div');
                                card.className = 'event-card';
                                card.innerHTML = `
                                    <h3 class="event-title">${event.title}</h3>
                                    <p class="event-description">${event.description}</p>
                                    <div class="event-meta">
                                        <p><strong>Créé par :</strong> ${event.username}</p>
                                        <p><strong>Participants :</strong> ${event.player_count}</p>
                                        <p><strong>Date :</strong> ${event.start_time} - ${event.end_date}</p>
                                    </div>
                                `;
                                eventList.appendChild(card);
                            });
                        } else {
                            eventList.innerHTML = '<p style="text-align: center; color: #ccc;">Aucun événement à afficher pour le moment.</p>';
                        }
                    })
                    .catch(error => console.error('Erreur :', error));
                });
            });
        </script>