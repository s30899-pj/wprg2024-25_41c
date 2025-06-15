<?php
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/classes/User.php';
require_once '../src/classes/Auth.php';

use classes\User;
use classes\Auth;

session_start();

Auth::requireRole('admin');

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: admin_panel.php');
    exit;
}

$userToEdit = User::getById($pdo, (int)$id);

if (!$userToEdit) {
    echo "Nie znaleziono użytkownika.";
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? '';
    $is_blocked = isset($_POST['is_blocked']) ? 1 : 0;

    if (!in_array($role, ['admin', 'organizer', 'user'])) {
        $errors[] = 'Nieprawidłowa rola.';
    }

    if (empty($errors)) {
        User::update($pdo, $id, $userToEdit['login'], $userToEdit['email'], $role, $is_blocked);
        $_SESSION['success'] = 'Dane użytkownika zostały zaktualizowane!';
        header('Location: admin_panel.php');
        exit;
    }
}

include 'assets/header.php';
?>

    <h2 class="page-title" style="text-align:center;">Edytuj użytkownika</h2>

<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $e): ?>
            <li class="message error"><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

    <form method="post" action="edit_user.php?id=<?= $id ?>">
        <label>Login:
            <input type="text" value="<?= htmlspecialchars($userToEdit['login']) ?>" readonly style="background:#eee; cursor:not-allowed;">
        </label>

        <label>Email:
            <input type="email" value="<?= htmlspecialchars($userToEdit['email']) ?>" readonly style="background:#eee; cursor:not-allowed;">
        </label>

        <label>Rola:
            <select name="role" required>
                <option value="user" <?= $userToEdit['role'] === 'user' ? 'selected' : '' ?>>Użytkownik</option>
                <option value="organizer" <?= $userToEdit['role'] === 'organizer' ? 'selected' : '' ?>>Organizator</option>
                <option value="admin" <?= $userToEdit['role'] === 'admin' ? 'selected' : '' ?>>Administrator</option>
            </select>
        </label>

        <label>
            <input type="checkbox" name="is_blocked" <?= !empty($userToEdit['is_blocked']) ? 'checked' : '' ?>>
            Zablokuj użytkownika
        </label>

        <input type="submit" value="Zapisz zmiany" style="margin-top: 15px;">
    </form>

<?php include 'assets/footer.php'; ?>