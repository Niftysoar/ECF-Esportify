<?php
// Démarre la session
session_start();
include('config.php');

// Vérification si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Hacher le mot de passe avant de le stocker
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Préparer la requête pour vérifier si le pseudo ou l'email existe déjà
    $query = "SELECT * FROM users WHERE username = :username OR email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['username' => $username, 'email' => $email]);

    if ($stmt->rowCount() > 0) {
        // Si l'utilisateur ou l'email existe déjà
        echo "<p style='color: red;'>Ce nom d'utilisateur ou cet email est déjà utilisé.</p>";
    } else {
        // Insérer les données dans la base de données
        $query = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'username' => $username,
            'password' => $hashed_password,
            'email' => $email
        ]);

        // Rediriger vers la page de connexion après l'inscription
        header("Location: /signin"); // Redirige vers la page de connexion
        exit;
    }
}
?>

    <section class="inscription">
        <h1>CREEZ-VOUS <span class="highlight">UN COMPTE</span></h1>
        <form action="/signup" method="POST" class="form">
            <div class="input-container">
                <input type="text" name="username" required>
                <label class="label" for="username">Nom d'utilisateur</label>
            </div>
            <div class="input-container">
                <input type="email" name="email" required>
                <label class="label" for="email">Email</label>
            </div>
            <div class="input-container">
                <input type="password" name="password" required>
                <label class="label" for="password">Entrez votre mot de passe</label>
            </div>
            <div class="input-container">
                <input type="password" id="password" name="password" required>
                <label class="label" for="password">Confirmez votre mot de passe</label>
            </div>
            <div class="other-container">
                <button type="submit" class="btn btn-highlight">INSCRIRE</button>
                <p>Deja un compte ? <a href="/signin">Connectez-vous</a></p>
            </div>
        </form>
    </section>