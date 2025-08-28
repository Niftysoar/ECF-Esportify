<?php
session_start();
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../../classes/EventManager.php');

// Redirection si non connecté ou mauvais rôle
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'organisateur' && $_SESSION['role'] !== 'admin')) {
    header('Location: /');
    exit();
}

// Génération du token CSRF si absent
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$eventManager = new EventManager($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification du token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Erreur CSRF : requête invalide.");
    }
    
    try {
        $eventManager->createEvent(
            htmlspecialchars(trim($_POST['title'])),
            htmlspecialchars(trim($_POST['description'])),
            $_POST['player_count'], // cast numérique
            htmlspecialchars($_POST['start_time']),
            htmlspecialchars($_POST['end_date']),
            $_SESSION['user_id']
        );

        header('Location: /dashboard');
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!-- Formulaire de création d'événement -->
<div class="container">
    <h1>Créer un <span class="highlight">événement</span></h1>
    <form action="/pages/auth/organisateur/create_event.php" method="POST" class="form">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

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