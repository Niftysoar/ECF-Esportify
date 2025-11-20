<?php
class ParticipationManager {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Accepte ou refuse une participation à un de ses propres événements
    public function updateStatus($user_id, $event_id, $status, $organizer_id) {
        $sql = "
            UPDATE participations p
            SET status = :status
            FROM events e
            WHERE p.event_id = e.id
            AND p.user_id = :user_id
            AND p.event_id = :event_id
            AND e.created_by = :organizer_id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'status' => $status,
            'user_id' => $user_id,
            'event_id' => $event_id,
            'organizer_id' => $organizer_id,
        ]);
    }

    // Récupère les inscriptions aux événements de l’organisateur
    public function getRegistrationsByOrganizer($organizer_id) {
        $sql = "
            SELECT u.username AS player, p.status, e.title AS event_title, e.start_time, 
                p.user_id, p.event_id
            FROM participations p
            JOIN users u ON p.user_id = u.id
            JOIN events e ON p.event_id = e.id
            WHERE e.created_by = :organizer_id
            ORDER BY e.start_time DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['organizer_id' => $organizer_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- Rejoindre un événement ---
    public function joinEvent($user_id, $event_id) {
        // Vérifie que l'événement existe et est validé
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE id = :event_id AND status = 'valide'");
        $stmt->execute(['event_id' => $event_id]);
        if ($stmt->rowCount() === 0) {
            throw new Exception("Événement non trouvé ou non validé.");
        }

        // Vérifie si l'utilisateur est déjà inscrit
        $stmt = $this->pdo->prepare("SELECT * FROM participations WHERE user_id = :user_id AND event_id = :event_id");
        $stmt->execute([
            'user_id' => $user_id,
            'event_id' => $event_id
        ]);
        if ($stmt->rowCount() > 0) {
            throw new Exception("Vous êtes déjà inscrit à cet événement.");
        }

        // Ajoute la participation
        $stmt = $this->pdo->prepare("
            INSERT INTO participations (user_id, event_id, status, joined) 
            VALUES (:user_id, :event_id, 'en_attente', FALSE)
        ");
        $stmt->execute([
            'user_id' => $user_id,
            'event_id' => $event_id
        ]);

        return true;
    }
}
?>