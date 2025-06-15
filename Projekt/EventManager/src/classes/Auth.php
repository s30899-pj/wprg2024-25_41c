<?php

namespace classes;

class Auth
{
    public static function login(\PDO $pdo, string $login, string $password): bool
    {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE login = :login");
        $stmt->execute(['login' => $login]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'login' => $user['login'],
                'email' => $user['email'],
                'role' => $user['role']
            ];
            return true;
        }
        return false;
    }

    public static function logout(): void
    {
        session_unset();
        session_destroy();
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