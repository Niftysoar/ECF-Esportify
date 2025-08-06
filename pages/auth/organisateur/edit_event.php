<?php
session_start();
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../../classes/EventManager.php');

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'organisateur' && $_SESSION['role'] !== 'admin')) {
    header("Location: /");
    exit();
}

$event_id = $_GET['id'] ?? null;
if (!$event_id) {
    die("ID d'événement manquant.");
}

// Récupération des infos de l'événement directement (sans méthode dédiée)
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = :id");
$stmt->execute([':id' => $event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    die("Événement non trouvé.");
}

$eventManager = new EventManager($pdo);
$error = '';
$success = '';

// Soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $eventManager->updateEvent(
            $event_id,
            $_POST['title'],
            $_POST['description'],
            $_POST['player_count'],
            $_POST['start_time'],
            $_POST['end_date']
        );
        $success = "Événement mis à jour avec succès.";
        // Rechargement des données mises à jour
        $stmt->execute([':id' => $event_id]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<div class="container">
    <h1>Modifier <span class="highlight">l'événement</span></h1>

    <?php if ($success): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="" method="POST" class="form">
        <div class="input-container">
            <input type="text" name="title" id="title" required value="<?= htmlspecialchars($event['title']) ?>">
            <label class="label" for="title">Titre</label>
        </div>

        <div class="input-container">
            <textarea name="description" id="description" required><?= htmlspecialchars($event['description']) ?></textarea>
            <label class="label" for="description">Description</label>
        </div>

        <div class="input-container">
            <input type="number" name="player_count" id="player_count" min="1" required value="<?= htmlspecialchars($event['player_count']) ?>">
            <label class="label" for="player_count">Nombre de joueurs</label>         
        </div>

        <div class="input-container">
            <input type="datetime-local" id="start_time" name="start_time" required value="<?= date('Y-m-d\TH:i', strtotime($event['start_time'])) ?>">
            <label class="label" for="start_time">Date de début</label>
        </div>

        <div class="input-container">
            <input type="datetime-local" name="end_date" id="end_date" required value="<?= date('Y-m-d\TH:i', strtotime($event['end_date'])) ?>">
            <label class="label" for="end_date">Date de fin</label>
        </div>

        <div class="other-container">
            <button type="submit" class="btn btn-highlight">Mettre à jour</button>
        </div>
    </form>
</div>