<?php

session_start();

include('../config.php');

// Rediriger si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: /signin');
    exit();
}

// Vérifier si l'utilisateur est connecté et a les droits organisateur ou admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organisateur' && $_SESSION['role'] !== 'admin') {
    header('Location: /'); // Rediriger vers la page de connexion si l'utilisateur n'est pas un organisateur ou admin
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        isset($_POST['title'], $_POST['description'], $_POST['player_count'], $_POST['start_time'], $_POST['end_date'])
    ) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $player_count = $_POST['player_count'];
        $start_time = $_POST['start_time'];
        $end_date = $_POST['end_date'];
        $created_by = $_SESSION['user_id'];

        // Calcul de la date à partir de laquelle l'événement peut être démarré (30 minutes avant)
        $can_start_from = date('Y-m-d H:i:s', strtotime($start_time) - 1800); // 1800s = 30min

        $pdo->exec("SELECT setval('events_id_seq', (SELECT MAX(id) FROM events));");
        $query = "INSERT INTO events (title, description, player_count, start_time, end_date, created_by, can_start_from, status) 
                  VALUES (:title, :description, :player_count, :start_time, :end_date, :created_by, :can_start_from, 'en_attente')";

        try {
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':player_count', $player_count);
            $stmt->bindParam(':start_time', $start_time);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->bindParam(':created_by', $created_by);
            $stmt->bindParam(':can_start_from', $can_start_from);
            $stmt->execute();

            header('Location: /dashboard');
            exit();
        } catch (PDOException $e) {
            echo "<p class='error'>Erreur : " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='error'>Veuillez remplir tous les champs.</p>";
    }
}
?>

<!-- Formulaire de création d'événement -->
<div class="container">
    <h1>Créer un <span class="highlight">événement</span></h1>
    <form action="/pages/event/create_event.php" method="POST" class="form">
        <div class="input-container">
            <input type="text" name="title" id="title" required>
            <label class="label" for="title">Titre</label>
        </div>

        <div class="input-container">
            <textarea name="description" id="description" required></textarea>
            <label class="label" for="description">Description</label>
        </div>

        <div class="input-container">
            <input type="number" name="player_count" id="player_count" min="1" required>
            <label class="label" for="player_count">Nombre de joueurs</label>         
        </div>

        <div class="input-container">
            <input type="datetime-local" id="start_time" name="start_time" required>
            <label for="start_time" class="label">Date de début</label>
        </div>

        <div class="input-container">
            <input type="datetime-local" name="end_date" id="end_date" required>
            <label class="label" for="end_time">Date de fin</label>
        </div>
        <div class="other-container">
            <button type="submit" class="btn btn-highlight">Créer l'événement</button>
        </div>
    </form>
</div>