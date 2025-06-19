<?php
require_once '../src/config.php';
require_once '../src/db.php';

session_start();

if (isset($_COOKIE['remember'])) {
    setcookie('remember', '', time() - 3600, '/');

    if (isset($_SESSION['user'])) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET remember_token = NULL, remember_token_expires = NULL WHERE id = ?");
            $stmt->execute([$_SESSION['user']['id']]);
        } catch (PDOException $e) {
            error_log("Błąd podczas czyszczenia tokena: " . $e->getMessage());
        }
    }
}

session_unset();
session_destroy();
header('Location: ' . BASE_URL . 'login.php');
exit;