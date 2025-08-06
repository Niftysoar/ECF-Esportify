<?php
class UserManager {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Enregistre un nouvel utilisateur
     */
    public function register($username, $email, $password, $role = 'joueur') {
        if (empty($username) || empty($email) || empty($password)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (username, email, password, role) 
                VALUES (:username, :email, :password, :role)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hash,
            ':role' => $role
        ]);

        return $this->pdo->lastInsertId();
    }

    /**
     * Vérifie la connexion utilisateur
     */
    public function login($username, $password) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            throw new Exception("Identifiants invalides.");
        }

        return $user;
    }

    /**
     * Met à jour le rôle d’un utilisateur
     */
    public function updateRole($user_id, $role) {
        $validRoles = ['joueur', 'organisateur', 'admin'];
        if (!in_array($role, $validRoles)) {
            throw new Exception("Rôle invalide.");
        }
        $sql = "UPDATE users SET role = :role WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':role' => $role, ':id' => $user_id]);
    }

    /**
     * Supprime un utilisateur sauf lui-même
     */
    public function deleteUser($user_id, $current_user_id) {
        if ($user_id == $current_user_id) {
            throw new Exception("Impossible de supprimer l'utilisateur connecté.");
        }
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $user_id]);
    }

    /**
     * Récupère tous les utilisateurs
     */
    public function getAllUsers() {
        $sql = "SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>