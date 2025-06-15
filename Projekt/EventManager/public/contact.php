<?php
require_once '../src/config.php';
require_once '../src/db.php';

session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name && $email && $message) {
        $logMessage = date('Y-m-d H:i:s') . " | Kontakt od $name <$email>: " . str_replace("\n", ' ', $message) . "\n";
        file_put_contents('../logs/contact.log', $logMessage, FILE_APPEND);
        $success = true;
    } else {
        $error = "Wszystkie pola są wymagane.";
    }
}

include 'assets/header.php';
?>

    <h2 class="page-title">Kontakt</h2>

<?php if (!empty($success)): ?>
    <p class="message success">Dziękujemy za wiadomość! Skontaktujemy się wkrótce.</p>
<?php elseif (!empty($error)): ?>
    <p class="message error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

    <form method="post">
        <label for="name">Imię i nazwisko</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Adres e-mail</label>
        <input type="email" id="email" name="email" required>

        <label for="message">Wiadomość</label>
        <textarea id="message" name="message" rows="6" required></textarea>

        <input type="submit" value="Wyślij wiadomość">
    </form>

<?php include 'assets/footer.php'; ?>