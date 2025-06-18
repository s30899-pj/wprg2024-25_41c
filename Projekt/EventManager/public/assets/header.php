<?php
require_once '../src/classes/Auth.php';
use classes\Auth;

Auth::checkRememberToken($pdo);

if (session_status() === PHP_SESSION_NONE) session_start();
$currentUser = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Manager</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="page-wrapper">
    <header>
        <h1><a href="index.php" style="color: white; text-decoration: none;">Event Manager</a></h1>
        <nav>
            <ul>
                <li><a href="index.php">Strona główna</a></li>
                <li><a href="events.php">Wydarzenia</a></li>

                <?php if ($currentUser): ?>
                    <?php if (in_array($currentUser['role'], ['admin', 'organizer'])): ?>
                        <li><a href="add_event.php">Dodaj wydarzenie</a></li>
                    <?php endif; ?>

                    <?php if ($currentUser['role'] === 'admin'): ?>
                        <li><a href="admin_panel.php">Panel admina</a></li>
                    <?php endif; ?>

                    <li><a href="profile.php">Mój profil</a></li>
                    <li><a href="logout.php">Wyloguj</a></li>

                <?php else: ?>
                    <li><a href="login.php">Zaloguj się</a></li>
                    <li><a href="register.php">Zarejestruj się</a></li>
                <?php endif; ?>

                <li><a href="contact.php">Kontakt</a></li>
            </ul>
        </nav>
    </header>
    <main class="container">