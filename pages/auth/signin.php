<?php
session_start();
require_once('../config.php');
require_once('../classes/UserManager.php');

// Vérifier si déjà connecté
if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['role'] == 'admin' ? "/admin" : "/dashboard"));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérification du token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Requête invalide (CSRF détecté).");
    }
    
    $username = htmlspecialchars(trim($_POST['username']));
    $password = trim($_POST['password']);

    $userManager = new UserManager($pdo);

    try {
        $user = $userManager->login($username, $password);

        // On stocke les infos en session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirection selon le rôle
        if ($user['role'] === 'admin') {
            header('Location: /admin');
        } elseif ($user['role'] === 'organisateur') {
            header('Location: /orga');
        } else {
            header('Location: /dashboard');
        }
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Générer un token CSRF
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<section class="connexion">
    <h1>Contents de <span class="highlight">vous voir !</span></h1>

    <?php if (!empty($error)): ?>
        <div class="popup-error">
            <p><?= htmlspecialchars($error) ?></p>
            <button onclick="this.parentElement.style.display='none'" class="btn btn-highlight">Fermer</button>
        </div>
    <?php endif; ?>

    <form id="login-form" action="/pages/auth/signin.php" method="POST" class="form">
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