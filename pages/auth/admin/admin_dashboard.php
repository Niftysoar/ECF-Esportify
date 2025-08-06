<?php
session_start();
require_once(__DIR__ . '/../../config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /');
    exit();
}

// STATISTIQUES UTILISATEURS
$stmt = $pdo->query("SELECT role, COUNT(*) AS total FROM users GROUP BY role");
$user_stats = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// STATISTIQUES ÉVÉNEMENTS (status : en_attente, valide, non_valide)
$stmt = $pdo->query("SELECT status, COUNT(*) AS total FROM events GROUP BY status");
$event_stats = [];
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $event_stats[$row['status']] = $row['total'];
}

// STATISTIQUES PARTICIPATIONS
$stmt = $pdo->query("SELECT COUNT(*) AS total FROM participations");
$registration_count = $stmt->fetchColumn();
?>

<section class="admin">
    <h1>Admin <span class="highlight">Dashboard</span></h1>

    <div class="card-container">
        <div class="card card-users">
            <h2>Utilisateurs</h2>
            <p>🎮 Joueurs: <?= $user_stats['joueur'] ?? 0 ?></p>
            <p>🛠️ Organisateurs: <?= $user_stats['organisateur'] ?? 0 ?></p>
            <p>👩‍💼 Admins: <?= $user_stats['admin'] ?? 0 ?></p>
        </div>

        <div class="card card-events">
            <h2>Événements</h2>
            <p>✅ Validés: <?= $event_stats['valide'] ?? 0 ?></p>
            <p>⏳ En attente: <?= $event_stats['en_attente'] ?? 0 ?></p>
            <p>❌ Rejetés: <?= $event_stats['non_valide'] ?? 0 ?></p>
            <p>📅 Événements totaux: <?= array_sum($event_stats) ?></p>
        </div>

        <div class="card card-registrations">
            <h2>Inscriptions</h2>
            <p>📥 Inscriptions totaux: <?= $registration_count ?></p>
        </div>
    </div>

    <div class="links">
        <a href="/eventmanager" class="btn btn-highlight">Parametres Événements</a>
        <a href="/usermanager" class="btn btn-highlight">Informations Utilisateurs</a>
    </div>
</section>

<style>
    .admin-summary { 
        max-width: 1000px; 
        margin: 2rem auto; 
        padding: 1rem; 
    }
    
    .card-container { 
        display: flex; 
        gap: 1rem; 
        justify-content: space-between; 
        margin-top: 2rem; 
        flex-wrap: wrap; 
    }

    .card { 
        flex: 1; 
        padding: 1rem; 
        border: 1px solid #ddd; 
        border-radius: 5px; 
        min-width: 250px; 
    }
        
    .card h2 { 
        margin-bottom: 1rem; 
        font-size: 1.5rem; 
    }

    .card-users, .card-events, .card-registrations {
        background-color: #1A1A1A;
        border: #FF4C4C 2px solid;
        padding: 25px;
        border-radius: 12px;
    }

    .links { 
        margin-top: 2rem; 
        display: flex; 
        gap: 1rem; 
        justify-content: center; 
    }
</style>