<?php
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/classes/User.php';

use classes\User;

session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $user = User::findByEmail($pdo, $email);

        if ($user) {
            $resetToken = bin2hex(random_bytes(32));
            User::setResetToken($pdo, $user['id'], $resetToken);

            $resetLink = BASE_URL . "reset_password.php?token=$resetToken";
            file_put_contents('../logs/password_resets.log', "Reset link for $email: $resetLink\n", FILE_APPEND);

            $success = 'Link resetujący hasło został wysłany na podany adres email!';
        } else {
            $error = 'Nie znaleziono użytkownika z podanym adresem email!';
        }
    } else {
        $error = 'Podaj poprawny adres email!';
    }
}

include 'assets/header.php';
?>

    <h2 class="page-title">Przypomnij hasło</h2>

<?php if ($error): ?>
    <div class="message error"><?= htmlspecialchars($error) ?></div>
<?php elseif ($success): ?>
    <div class="message success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

    <form method="post" style="max-width:400px; margin: 20px auto;">
        <label for="email">Adres email</label>
        <input type="email" name="email" id="email" required>

        <input type="submit" value="Wyślij link resetujący">
    </form>

<?php include 'assets/footer.php'; ?>