<?php
 // Assurez-vous que votre config.php est bien inclus

session_start();
require '../config.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Vous devez être connecté pour rejoindre un événement.";
    header('Location: /lives');
    exit;
}

if (isset($_GET['event_id'])) {
    $event_id = (int) $_GET['event_id'];
    $user_id = $_SESSION['user_id'];

    // Vérifie que l'événement existe et est validé
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = :event_id AND status = 'valide'");
    $stmt->execute(['event_id' => $event_id]);

    if ($stmt->rowCount() === 0) {
        $_SESSION['error_message'] = "Événement non trouvé ou non validé.";
        header("Location: /dashboard");
        exit;
    }

    // Vérifie si l'utilisateur a déjà une participation
    $stmt = $pdo->prepare("SELECT * FROM participations WHERE user_id = :user_id AND event_id = :event_id");
    $stmt->execute([
        'user_id' => $user_id,
        'event_id' => $event_id
    ]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['error_message'] = "Vous êtes déjà inscrit à cet événement.";
        header("Location: /dashboard");
        exit;
    }

    // Insère la participation (par défaut en attente)
    $stmt = $pdo->prepare("INSERT INTO participations (user_id, event_id, status, joined) VALUES (:user_id, :event_id, 'en_attente', 0)");
    $success = $stmt->execute([
        'user_id' => $user_id,
        'event_id' => $event_id
    ]);

    if ($success) {
        header("Location: /dashboard");
        exit();
    } else {
        echo "Une erreur est survenue lors de votre demande de participation.";
    }

} else {
    echo "Aucun événement sélectionné.";
}
?>