<?php

// Démarrer la session
session_start();

// Inclure le fichier de configuration pour la base de données
require_once('../config.php');

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    // Rediriger vers le tableau de bord en fonction du rôle
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: /dashboard");
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Vérifier les informations de connexion dans la base de données
    $query = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $query->execute(['username' => $username]);
    $user = $query->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Si le mot de passe est correct, démarrer la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Stocker le rôle de l'utilisateur (admin ou user)

        // Rediriger vers le tableau de bord en fonction du rôle
        if ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php"); // Tableau de bord admin
        } else {
            header("Location: /dashboard"); // Tableau de bord utilisateur classique
        }
        exit();
    } else {
        $error = "Identifiants incorrects.";
    }
}
?>

    <section class="connexion">
        <h1>CONTENTS DE <span class="highlight">VOUS VOIR !</span></h1>

        <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="/pages/auth/signin.php" method="POST" class="form">
            <div class="input-container">
                <input type="text" id="username" name="username" required>
                <label class="label" for="username">Nom d'utilisateur</label>
            </div>
            <div class="input-container">
                <input type="password" id="password" name="password" required>
                <label class="label" for="password">Entrez votre mot de passe</label>       
            </div>
            <p><a href="/forgot-password">Mot de passe oublié ?</a></p>
            <div class="other-container">
                <button type="submit" class="btn btn-highlight">CONNEXION</button>
                <p>Nouveau ? <a href="/signup">Inscrivez-vous</a></p>
            </div>
        </form>
    </section>