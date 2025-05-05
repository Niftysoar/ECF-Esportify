<?php
// Connexion à la base de données
require_once 'config.php';

// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté et a les droits admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php'); // Rediriger vers la page de connexion si l'utilisateur n'est pas un admin
    exit;
}

// Traitement de la validation ou du rejet
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_id'], $_POST['action'])) {
    $event_id = $_POST['event_id'];
    $new_status = ($_POST['action'] == 'accept') ? 'validé' : 'rejeté';

    // Mise à jour du statut de l'événement
    $updateStmt = $pdo->prepare("UPDATE events SET status = :new_status WHERE id = :event_id");
    $updateStmt->execute(['new_status' => $new_status, 'event_id' => $event_id]);

    // Redirection vers la page après mise à jour
    header('Location: admin_dashboard.php');
    exit;
}

// Récupération des événements en attente de validation
$stmt = $pdo->prepare("SELECT e.id, e.title, e.start_date, e.end_date, e.player_count, u.username 
                       FROM events e 
                       INNER JOIN users u ON e.created_by = u.id 
                       WHERE e.status = 'en attente'");
$stmt->execute();
$pendingEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Admin</title>
    <style>
        /* Styles principaux */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
        }

        h1 {
            text-align: center;
            color: #fff;
            background-color: #3498db;
            padding: 20px;
            margin: 0;
        }

        .nav-buttons {
            text-align: center;
            margin-top: 20px;
        }

        .nav-buttons a {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 10px;
        }

        .nav-buttons a:hover {
            background-color: #2980b9;
        }

        h2 {
            color: #2c3e50;
            text-align: center;
            margin-top: 20px;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #3498db;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f0f0f0;
        }

        .btn-accept,
        .btn-reject {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-accept {
            background-color: #2ecc71;
            color: #fff;
        }

        .btn-reject {
            background-color: #e74c3c;
            color: #fff;
        }

        .btn-accept:hover {
            background-color: #27ae60;
        }

        .btn-reject:hover {
            background-color: #c0392b;
        }

        footer {
            text-align: center;
            padding: 15px;
            background-color: #2c3e50;
            color: #fff;
        }

        footer a {
            color: #3498db;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    <h1>Tableau de bord Admin</h1>

    <!-- Boutons de navigation -->
    <div class="nav-buttons">
        <a href="index.html">Accueil</a>
        <a href="events.php">Événements</a>
        <a href="logout.php" style="background-color: #dc3545;">Se déconnecter</a></p>
    </div>

    <h2>Événements en attente de validation</h2>

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
                        <td><?= htmlspecialchars($event['start_date']); ?></td>
                        <td><?= htmlspecialchars($event['end_date']); ?></td>
                        <td><?= htmlspecialchars($event['player_count']); ?></td>
                        <td><?= htmlspecialchars($event['username']); ?></td>
                        <td>
                            <form action="admin_dashboard.php" method="POST" style="display:inline-block;">
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

    <!-- Zone pour le graphique -->
    <canvas id="eventsChart" width="400" height="200"></canvas>

    <footer>
        <p>© 2024 Esports
    </footer>

    <!-- Script JavaScript pour charger le graphique -->
    <script>
        fetch('get_events_data.php')
            .then(response => response.json())
            .then(data => {
                // Vérification des données récupérées
                console.log(data);

                const labels = data.map(item => item.event_date); // Les dates
                const counts = data.map(item => item.total_events); // Les nombres

                const ctx = document.getElementById('eventsChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line', // ou 'bar' pour un graphique à barres
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Nombre d\'événements par jour',
                            data: counts,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Erreur:', error));
    </script>

</body>

</html>