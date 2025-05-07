<?php
include('../config.php');
session_start();

// Rediriger si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: /signin');
    exit();
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
            <label for="title">Titre de l'événement:</label>
            <input type="text" name="title" id="title" required>
        </div>

        <div class="input-container">
            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>
        </div>

        <div class="input-container">
            <label for="player_count">Nombre de joueurs:</label>
            <input type="number" name="player_count" id="player_count" required>
        </div>

        <div class="input-container">
            <label for="start_time">Date de début:</label>
            <input type="datetime-local" name="start_time" id="start_time" required>
        </div>

        <div class="input-container">
            <label for="end_date">Date de fin:</label>
            <input type="datetime-local" name="end_date" id="end_date" required>
        </div>
        <div class="other-container">
            <button type="submit" class="btn btn-highlight">Créer l'événement</button>
        </div>
    </form>
</div>