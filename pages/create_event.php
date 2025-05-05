<?php
// Inclure le fichier de configuration pour la connexion à la base de données
include('config.php');

// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header('Location: login.php');  // Rediriger si l'utilisateur n'est pas connecté
    exit();
}

// Récupérer les données du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérifier que les données sont présentes
    if (isset($_POST['title'], $_POST['description'], $_POST['player_count'], $_POST['start_date'], $_POST['end_date'])) {

        $title = $_POST['title'];
        $description = $_POST['description'];
        $player_count = $_POST['player_count'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $user_id = $_SESSION['username']; // Utiliser l'ID de l'utilisateur connecté

        // Préparer la requête d'insertion
        $query = "INSERT INTO events (title, description, player_count, start_date, end_date, created_by, status) 
                  VALUES (:title, :description, :player_count, :start_date, :end_date, :created_by, 'en attente')";

        // Exécuter la requête
        try {
            // Utiliser la connexion PDO pour préparer la requête
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':player_count', $player_count);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->bindParam(':created_by', $username); // Bind l'ID de l'utilisateur
            $stmt->execute();

            // Rediriger vers le tableau de bord de l'utilisateur après l'insertion
            header('Location: dashboard.php');
            exit(); // Terminer le script pour éviter toute autre exécution
        } catch (PDOException $e) {
            echo "<p class='error'>Erreur lors de l'insertion : " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='error'>Veuillez remplir tous les champs.</p>";
    }
}
?>



<!-- Formulaire de création d'événement -->
<div class="container">
    <h2>Créer un événement</h2>
    <form action="create_event.php" method="post">
        <div class="form-group">
            <label for="title">Titre de l'événement:</label>
            <input type="text" name="title" id="title" required>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>
        </div>

        <div class="form-group">
            <label for="player_count">Nombre de joueurs:</label>
            <input type="number" name="player_count" id="player_count" required>
        </div>

        <div class="form-group">
            <label for="start_date">Date de début:</label>
            <input type="datetime-local" name="start_date" id="start_date" required>
        </div>

        <div class="form-group">
            <label for="end_date">Date de fin:</label>
            <input type="datetime-local" name="end_date" id="end_date" required>
        </div>

        <button type="submit" class="btn">Créer l'événement</button>
    </form>
</div>

<!-- Styles CSS -->
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f7fc;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 100%;
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    h2 {
        text-align: center;
        color: #007bff;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-size: 16px;
        color: #555;
        display: block;
        margin-bottom: 5px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-top: 5px;
    }

    .form-group textarea {
        resize: vertical;
        height: 150px;
    }

    .btn {
        display: block;
        width: 100%;
        padding: 12px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn:hover {
        background-color: #0056b3;
    }

    .error {
        color: red;
        text-align: center;
        margin-top: 10px;
    }

    .success {
        color: green;
        text-align: center;
        margin-top: 10px;
    }
</style>