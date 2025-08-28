<?php
session_start();
require_once('../config.php');
require_once('../classes/UserManager.php');

$userManager = new UserManager($pdo);

// Génération du token CSRF si absent
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Vérifier si déjà connecté
if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['role'] == 'admin' ? "/admin" : "/dashboard"));
    exit();
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérification du token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Erreur CSRF : requête invalide.");
    }
    
    $username = htmlspecialchars(trim($_POST['username']));
    $password = trim($_POST['password']);

    try {
        $user = $userManager->login($username, $password);

        // Stockage des informations de session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirection selon le rôle
        $redirect = $user['role'] === 'admin' ? "/admin" : "/dashboard";
        header("Location: $redirect");
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<section class="connexion">
    <h1>Contents de <span class="highlight">vous voir !</span></h1>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form id="login-form" class="form" method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <div class="input-container">
            <input type="text" name="username" id="username" required>
            <label class="label" for="username">Nom d'utilisateur</label>
        </div>
        <div class="input-container">
            <input type="password" id="password" name="password" required>
            <label class="label" for="password">Entrez votre mot de passe</label>       
        </div>
        <p><a href="/forgot-password">Mot de passe oublié ?</a></p>
        <div class="other-container">
            <div id="error-message" style="color: red; margin-bottom: 1rem;"></div>
            <button type="submit" class="btn btn-highlight">CONNEXION</button>
            <p>Nouveau ? <a href="/signup">Inscrivez-vous</a></p>
        </div>
    </form>
</section>

<script type="module" src="/Scripts/Routeur/login.js"></script>