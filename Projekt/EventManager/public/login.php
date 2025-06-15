<?php
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/classes/User.php';
require_once '../src/classes/Auth.php';

use classes\User;
use classes\Auth;

session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = User::findByLogin($pdo, $login);

    if ($user) {
        if (!empty($user['is_blocked'])) {
            $error = 'Twoje konto jest zablokowane.';
        } elseif (password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'login' => $user['login'],
                'email' => $user['email'],
                'role' => $user['role']
            ];

            if (isset($_POST['remember_me'])) {
                $rememberToken = bin2hex(random_bytes(32));
                $expiry = time() + 60 * 60 * 24 * 30;
                setcookie('remember', $rememberToken, $expiry, '/');

                $stmt = $pdo->prepare("INSERT INTO remember_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
                $stmt->execute([$user['id'], $rememberToken, date('Y-m-d H:i:s', $expiry)]);
            }

            header('Location: index.php');
            exit;
        } else {
            $error = 'Nieprawidłowy login lub hasło.';
        }
    } else {
        $error = 'Nieprawidłowy login lub hasło.';
    }
}

include 'assets/header.php';
?>

    <h2 class="page-title">Logowanie</h2>

<?php if ($error): ?>
    <div class="message error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

    <form method="post" style="max-width:400px; margin: 20px auto;">
        <label for="login">Login</label>
        <input type="text" name="login" id="login" required>

        <label for="password">Hasło</label>
        <input type="password" name="password" id="password" required>

        <label>
            <input type="checkbox" name="remember_me"> Zapamiętaj mnie
        </label>

        <input type="submit" value="Zaloguj się">

        <p style="text-align: center; margin-top: 15px;">
            <a href="forgot_password.php">Zapomniałeś hasła?</a>
        </p>
    </form>

<?php include 'assets/footer.php'; ?>