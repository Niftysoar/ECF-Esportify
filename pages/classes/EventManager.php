<?php

require_once __DIR__ . '/../entity/Event.php';

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
    public function getEvents($filters = []) {
        $sql = "SELECT e.*, u.username
                FROM events e
                JOIN users u ON e.created_by = u.id
                WHERE e.status = 'valide'";

        $params = [];

        if (!empty($filters['player_count'])) {
            $sql .= " AND e.player_count >= :player_count";
            $params[':player_count'] = $filters['player_count'];
        }
        if (!empty($filters['date'])) {
            $sql .= " AND DATE(e.start_time) = :date";
            $params[':date'] = $filters['date'];
        }
        if (!empty($filters['username'])) {
            $sql .= " AND u.username LIKE :username";
            $params[':username'] = "%" . $filters['username'] . "%";
        }

        $sql .= " ORDER BY e.start_time ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEventsByOrganizer($organizer_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE created_by = :id ORDER BY start_time DESC");
        $stmt->execute(['id' => $organizer_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    /**
     * Écrit un log de lancement d'événement
     */
    public function logLaunch(int $event_id, int $organizer_id): void {
        // vérifier si l'event appartient à l'organisateur
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE id = :id AND created_by = :org_id");
        $stmt->execute([
            ':id' => $event_id,
            ':org_id' => $organizer_id
        ]);

        if ($stmt->rowCount() === 0) {
            throw new Exception("Événement non trouvé ou non autorisé.");
        }

        $log = "[" . date('Y-m-d H:i:s') . "] Lancement de l'événement ID $event_id par l'organisateur ID $organizer_id\n";
        file_put_contents(__DIR__ . '/../event_log.txt', $log, FILE_APPEND);
    }
}
?>