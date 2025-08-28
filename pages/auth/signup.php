<?php
session_start();
require_once('../config.php');
require_once('../classes/UserManager.php');

// Protection CSRF
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Requête invalide (CSRF détecté).");
    }

    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    $userManager = new UserManager($pdo);

    try {
        $userManager->register($username, $email, $password);

        // Redirection si succès
        header("Location: /signin");
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Générer un token CSRF
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<section class="inscription">
    <h1>Créez-vous <span class="highlight">un compte</span></h1>

    <?php if (!empty($error)): ?>
        <div class="popup-error">
            <p><?= htmlspecialchars($error) ?></p>
            <button onclick="this.parentElement.style.display='none'" class="btn btn-highlight">Fermer</button>
        </div>
    <?php endif; ?>

    <form action="/pages/auth/signup.php" method="POST" class="form">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <div class="input-container">
            <input type="text" name="username" id="username" required>
            <label class="label" for="username">Nom d'utilisateur</label>
        </div>
        <div class="input-container">
            <input type="email" name="email" id="email" required>
            <label class="label" for="email">Email</label>
        </div>
        <div class="input-container">
            <input type="password" name="password" id="password" required>
            <label class="label" for="password">Mot de passe</label>
        </div>
        <div class="other-container">
            <button type="submit" class="btn btn-highlight">INSCRIRE</button>
            <p>Déjà un compte ? <a href="/signin">Connectez-vous</a></p>
        </div>
    </form>
</section>