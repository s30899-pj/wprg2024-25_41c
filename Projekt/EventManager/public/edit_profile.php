<?php
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/classes/User.php';
require_once '../src/classes/Auth.php';

use classes\User;
use classes\Auth;

session_start();
Auth::requireLogin();

$userId = (int)$_SESSION['user']['id'];
$user = User::getById($pdo, $userId);

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($login) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE login = ? AND id != ?");
        $stmt->execute([$login, $userId]);

        if ($stmt->fetchColumn() > 0) {
            $errors[] = 'Login lub email już istnieje!';
        } else {
            $stmt = $pdo->prepare("UPDATE users SET login = ? WHERE id = ?");
            $stmt->execute([$login, $userId]);

            $_SESSION['user']['login'] = $login;

            $success = 'Dane profilowe zostały zaktualizowane!';
        }
    }

    if ($password) {
        if ($password === $confirm) {
            User::updatePassword($pdo, $userId, $password);
            $success = $success ? $success . ' Hasło zostało zmienione!' : 'Hasło zostało zmienione!';
        } else {
            $errors[] = 'Hasła nie są identyczne!';
        }
    }
}

include 'assets/header.php';
?>

    <h2 class="page-title">Edytuj swój profil</h2>

<?php if ($success): ?>
    <div class="message success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $error): ?>
            <li class="message error"><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

    <form method="post">
        <label for="login">Login</label>
        <input type="text" name="login" id="login" value="<?= htmlspecialchars($user['login']) ?>" required>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" readonly style="background:#eee; cursor:not-allowed; color: #666">

        <h3 style="margin-top: 20px;">Zmiana hasła</h3>
        <p style="margin-bottom: 15px; color: #666;">Wypełnij tylko jeśli chcesz zmienić hasło</p>

        <label for="password">Nowe hasło</label>
        <input type="password" name="password" id="password">

        <label for="confirm_password">Powtórz nowe hasło</label>
        <input type="password" name="confirm_password" id="confirm_password">

        <input type="submit" value="Zapisz zmiany">
    </form>

<?php include 'assets/footer.php'; ?>