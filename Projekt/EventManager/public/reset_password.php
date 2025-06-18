<?php
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/classes/User.php';

use classes\User;

session_start();

$error = '';
$token = $_GET['token'] ?? '';

if (!$token) {
    $_SESSION['error'] = 'Brak tokenu resetowania!';
    header('Location: login.php');
    exit;
}

$resetUser = User::findByResetToken($pdo, $token);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($resetUser) {
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($password === $confirm) {
            User::updatePassword($pdo, $resetUser['id'], $password);
            User::clearResetToken($pdo, $resetUser['id']);

            $_SESSION['success'] = 'Hasło zostało pomyślnie zresetowane!';
            header('Location: login.php');
            exit;
        } else {
            $error = 'Hasła nie są identyczne!';
        }
    } else {
        $error = 'Token jest nieprawidłowy lub wygasł!';
    }
}

include 'assets/header.php';
?>

    <h2 class="page-title">Resetowanie hasła</h2>

<?php if ($error): ?>
    <div class="message error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($resetUser): ?>
    <form method="post" style="max-width:400px; margin: 20px auto;">
        <label for="password">Nowe hasło</label>
        <input type="password" name="password" id="password" required>

        <label for="confirm_password">Powtórz hasło</label>
        <input type="password" name="confirm_password" id="confirm_password" required>

        <input type="submit" value="Zresetuj hasło">
    </form>
<?php else: ?>
    <div class="message error">Nieprawidłowy lub przedawniony token resetowania!</div>
    <p style="text-align:center;">
        <a href="forgot_password.php">Wygeneruj nowy link resetujący</a>
    </p>
<?php endif; ?>

<?php include 'assets/footer.php'; ?>