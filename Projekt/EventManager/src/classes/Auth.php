<?php

namespace classes;

class Auth
{
    public static function checkRememberToken(\PDO $pdo): void
    {
        if (!isset($_SESSION['user']) && isset($_COOKIE['remember'])) {
            $token = $_COOKIE['remember'];
            $stmt = $pdo->prepare("SELECT * FROM users WHERE remember_token = ? AND remember_token_expires > NOW()");
            $stmt->execute([$token]);
            $user = $stmt->fetch();

            if ($user) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'login' => $user['login'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];
            }
        }
    }

    public static function requireLogin(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: login.php');
            exit;
        }
    }

    public static function requireRole(string $role): void
    {
        self::requireLogin();
        if ($_SESSION['user']['role'] !== $role) {
            header('Location: index.php');
            exit;
        }
    }

    public static function requireRoles(array $roles): void
    {
        self::requireLogin();
        if (!in_array($_SESSION['user']['role'], $roles)) {
            header('Location: index.php');
            exit;
        }
    }
}