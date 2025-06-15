<?php
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/classes/User.php';

use classes\User;

session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm) {
        $error = 'Hasła nie są takie same.';
    } elseif (User::exists($pdo, $login, $email)) {
        $error = 'Login lub e-mail już istnieje.';
    } else {
        if (User::create($pdo, $login, $email, $password)) {
            $success = 'Rejestracja zakończona sukcesem. Możesz się zalogować.';
        } else {
            $error = 'Wystąpił błąd podczas rejestracji.';
        }
    }
}

include 'assets/header.php';
?>

    <h2 class="page-title">Rejestracja</h2>

<?php if ($error): ?>
    <p class="message error"><?= htmlspecialchars($error) ?></p>
<?php elseif ($success): ?>
    <p class="message success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

    <form method="post">
        <label for="login">Login</label>
        <input type="text" name="login" id="login" required>

        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Hasło</label>
        <input type="password" name="password" id="password" required>

        <label for="confirm_password">Powtórz hasło</label>
        <input type="password" name="confirm_password" id="confirm_password" required>

        <input type="submit" value="Zarejestruj się">
    </form>

<?php include 'assets/footer.php'; ?>