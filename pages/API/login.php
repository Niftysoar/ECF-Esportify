<?php
session_start();
require_once('../config.php');

header("Content-Type: application/json");

$input = json_decode(file_get_contents('php://input'), true);

$username = $input['username'] ?? '';
$password = $input['password'] ?? '';

$query = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$query->execute(['username' => $username]);

$user = $query->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    echo json_encode([
        "success" => true,
        "role" => $user['role'],
        "token" => session_id()
    ]);
} else {
    echo json_encode([
        "success" => false,
        "error" => "Identifiants incorrects."
    ]);
}