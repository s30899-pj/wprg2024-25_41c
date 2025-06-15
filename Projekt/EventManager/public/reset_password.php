<?php
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/classes/User.php';

use classes\User;

session_start();

$error = '';
$token = $_GET['token'] ?? '';

if (!$token) {
    header('Location: login.php');
    exit;
}

$user = User::findByResetToken($pdo, $token);

if (!$user) {
    $error = 'Nieprawidłowy lub przedawniony token resetowania!';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($password === $confirm) {
        User::updatePassword($pdo, $user['id'], $password);
        User::clearResetToken($pdo, $user['id']);
        $_SESSION['success'] = 'Hasło zostało pomyślnie zresetowane!';
        header('Location: login.php');
        exit;
    } else {
        $error = 'Hasła nie są identyczne!';
    }
}

include 'assets/header.php';
?>

    <h2 class="page-title">Resetowanie hasła</h2>

<?php if ($error): ?>
    <div class="message error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($user): ?>
    <form method="post" style="max-width:400px; margin: 20px auto;">
        <label for="password">Nowe hasło</label>
        <input type="password" name="password" id="password" required>

        <label for="confirm_password">Powtórz hasło</label>
        <input type="password" name="confirm_password" id="confirm_password" required>

        <input type="submit" value="Zresetuj hasło">
    </form>
<?php endif; ?>

<?php include 'assets/footer.php'; ?>