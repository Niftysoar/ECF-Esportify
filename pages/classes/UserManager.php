<?php

require_once __DIR__ . '/../entity/User.php';

class UserManager {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Enregistre un nouvel utilisateur
     */
    public function register($username, $email, $password) {
        // Vérifier si l’utilisateur existe déjà
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $stmt->execute([
            ':username' => $username,
            ':email' => $email
        ]);

        if ($stmt->rowCount() > 0) {
            throw new Exception("Ce nom d'utilisateur ou cet email est déjà utilisé.");
        }

        // Réaligner la séquence pour éviter l'erreur de clé dupliquée
        $this->pdo->exec("SELECT setval('users_id_seq', COALESCE((SELECT MAX(id) FROM users), 1));");

        // Hacher le mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insérer le nouvel utilisateur
        $stmt = $this->pdo->prepare("
            INSERT INTO users (username, email, password, role, created_at) 
            VALUES (:username, :email, :password, 'joueur', NOW())
        ");
        $stmt->execute([
            ':username' => $username,
            ':email'    => $email,
            ':password' => $hashed_password
        ]);
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