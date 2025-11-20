<?php
class User {
    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private string $role;
    private string $createdAt;

    public function __construct(int $id, string $username, string $email, string $password, string $role, string $createdAt) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->createdAt = $createdAt;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getUsername(): string { return $this->username; }
    public function getEmail(): string { return $this->email; }
    public function getRole(): string { return $this->role; }
    public function getCreatedAt(): string { return $this->createdAt; }

    // Setter (optionnels, selon besoin)
    public function setUsername(string $username): void { $this->username = $username; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setRole(string $role): void { $this->role = $role; }
}