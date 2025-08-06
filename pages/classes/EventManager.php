<?php
class EventManager {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // --- Création d'un événement ---
    public function createEvent($title, $description, $player_count, $start_time, $end_date, $created_by) {
        if (empty($title) || empty($description) || empty($player_count) || empty($start_time) || empty($end_date)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        // Calcul de la date à partir de laquelle l'événement peut être démarré (30 minutes avant)
        $can_start_from = date('Y-m-d H:i:s', strtotime($start_time) - 1800);

        // Séquence PostgreSQL (si tu es sur PostgreSQL)
        $this->pdo->exec("SELECT setval('events_id_seq', (SELECT MAX(id) FROM events));");

        $sql = "INSERT INTO events (title, description, player_count, start_time, end_date, created_by, can_start_from, status) 
                VALUES (:title, :description, :player_count, :start_time, :end_date, :created_by, :can_start_from, 'en_attente')";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':title'          => $title,
            ':description'    => $description,
            ':player_count'   => $player_count,
            ':start_time'     => $start_time,
            ':end_date'       => $end_date,
            ':created_by'     => $created_by,
            ':can_start_from' => $can_start_from
        ]);
    }

    // --- Récupérer tous les événements validés ---
    public function getEvents() {
        $stmt = $this->pdo->query("SELECT * FROM events WHERE status = 'valide'");
        return $stmt->fetchAll();
    }

    // --- Rejoindre un événement ---
    public function joinEvent($eventId, $userId) {
        // Vérifier si déjà inscrit
        $stmt = $this->pdo->prepare("SELECT * FROM participations WHERE event_id = :event_id AND user_id = :user_id");
        $stmt->execute([':event_id' => $eventId, ':user_id' => $userId]);

        if ($stmt->rowCount() > 0) {
            throw new Exception("Vous êtes déjà inscrit à cet événement.");
        }

        $stmt = $this->pdo->prepare("INSERT INTO participations (event_id, user_id, status) VALUES (:event_id, :user_id, 'en_attente')");
        $stmt->execute([':event_id' => $eventId, ':user_id' => $userId]);

        return true;
    }

    // --- MODIFIER un événement ---
    public function updateEvent($id, $title, $description, $player_count, $start_time, $end_date) {
        if (empty($title) || empty($description) || empty($player_count) || empty($start_time) || empty($end_date)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        // Mise à jour de la date de début possible (30 min avant)
        $can_start_from = date('Y-m-d H:i:s', strtotime($start_time) - 1800);

        $sql = "UPDATE events 
                SET title = :title,
                    description = :description,
                    player_count = :player_count,
                    start_time = :start_time,
                    end_date = :end_date,
                    can_start_from = :can_start_from
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'             => $id,
            ':title'          => $title,
            ':description'    => $description,
            ':player_count'   => $player_count,
            ':start_time'     => $start_time,
            ':end_date'       => $end_date,
            ':can_start_from' => $can_start_from
        ]);
    }
}
?>