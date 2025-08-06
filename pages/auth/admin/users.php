<?php
session_start();
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../../classes/UserManager.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /');
    exit();
}

$userManager = new UserManager($pdo);
$message = null;

// ---- Gestion des actions ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['user_id'])) {
    $userId = (int) $_POST['user_id'];

    try {
        if ($_POST['action'] === 'delete') {
            $userManager->deleteUser($userId, $_SESSION['user_id']);
            $message = "Utilisateur supprimé avec succès.";
        }

        if ($_POST['action'] === 'update_role' && !empty($_POST['new_role'])) {
            $userManager->updateRole($userId, $_POST['new_role']);
            $message = "Rôle mis à jour avec succès.";
        }

        // Rafraîchissement après action
        header("Location: ".$_SERVER['PHP_SELF']."?msg=" . urlencode($message));
        exit();
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}

// Récupère tous les utilisateurs
$users = $userManager->getAllUsers();
if (isset($_GET['msg'])) {
    $message = htmlspecialchars($_GET['msg']);
}
?>

<section class="admin">
    <h1>Utilisateurs <span class="highlight">enregistrés</span></h1>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <a href="/admin" class="btn btn-highlight">Retour au tableau de bord</a>

    <?php if (empty($users)): ?>
        <div class="alert alert-info mt-4">Aucun utilisateur enregistré pour le moment.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped mt-4">
            <thead class="table-dark">
                <tr>
                    <th>Nom d'utilisateur</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['username']); ?></td>
                        <td><?= htmlspecialchars($u['email']); ?></td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="user_id" value="<?= $u['id']; ?>">
                                <select name="new_role" <?= $u['id'] === $_SESSION['user_id'] ? 'disabled' : ''; ?>>
                                    <option value="joueur" <?= $u['role'] === 'joueur' ? 'selected' : ''; ?>>Joueur</option>
                                    <option value="organisateur" <?= $u['role'] === 'organisateur' ? 'selected' : ''; ?>>Organisateur</option>
                                    <option value="admin" <?= $u['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                </select>
                                <?php if ($u['id'] !== $_SESSION['user_id']): ?>
                                    <button type="submit" name="action" value="update_role" class="btn-accept">Mettre à jour</button>
                                <?php endif; ?>
                            </form>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($u['created_at'])); ?></td>
                        <td>
                            <?php if ($u['id'] !== $_SESSION['user_id']): ?>
                                <form method="post" onsubmit="return confirm('Confirmer la suppression de <?= htmlspecialchars($u['username']); ?> ?');">
                                    <input type="hidden" name="user_id" value="<?= $u['id']; ?>">
                                    <button type="submit" name="action" value="delete" class="btn-reject">Supprimer</button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted">👑 Administrateur actuel</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>