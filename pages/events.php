<?php
// events.php

session_start();
require_once('config.php');

// Initialisation des filtres
$filter_player_count = isset($_GET['player_count']) ? $_GET['player_count'] : '';
$filter_date = isset($_GET['date']) ? $_GET['date'] : '';
$filter_username = isset($_GET['username']) ? $_GET['username'] : '';

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

        <h1>Événements E-sport à venir</h1>

        <!-- Formulaire de filtre -->
        <form id="filter-form" style="text-align: center; margin-bottom: 20px;">
            <input type="number" id="filter-player-count" placeholder="Nombre de joueurs min">
            <input type="date" id="filter-date">
            <input type="text" id="filter-username" placeholder="Pseudo créateur">
            <button type="button" id="apply-filter">Filtrer</button>
        </form>

        <div class="event-list" id="event-list">
            <?php if (count($events) > 0): ?>
                <?php foreach ($events as $event): ?>
                    <div class="event-card">
                        <h3 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p class="event-description"><?php echo htmlspecialchars($event['description']); ?></p>
                        <div class="event-meta">
                            <p><strong>Créé par :</strong> <?php echo htmlspecialchars($event['username']); ?></p>
                            <p><strong>Participants :</strong> <?php echo htmlspecialchars($event['player_count']); ?></p>
                            <p><strong>Date :</strong> <?php echo htmlspecialchars($event['start_time']); ?> - <?php echo htmlspecialchars($event['end_date']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: #ccc;">Aucun événement à afficher pour le moment.</p>
            <?php endif; ?>
        </div>

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