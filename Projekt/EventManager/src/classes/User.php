<?php

namespace classes;

class User
{
    public static function getAll(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT id, login, email, role, is_blocked FROM users ORDER BY id ASC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getById(\PDO $pdo, int $id): ?array
    {
        $stmt = $pdo->prepare("SELECT id, login, email, role, is_blocked FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public static function findById($pdo, $id) {
        if (!$id) return null;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function findByLogin(\PDO $pdo, string $login): ?array
    {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE login = :login LIMIT 1");
        $stmt->execute(['login' => $login]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public static function findByEmail(\PDO $pdo, string $email): ?array
    {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public static function count(\PDO $pdo): int
    {
        $stmt = $pdo->query("SELECT COUNT(*) FROM users");
        return (int)$stmt->fetchColumn();
    }

    public static function update(\PDO $pdo, int $id, string $login, string $email, string $role, int $is_blocked = 0): void
    {
        $stmt = $pdo->prepare("UPDATE users SET login = :login, email = :email, role = :role, is_blocked = :is_blocked WHERE id = :id");
        $stmt->execute([
            'login' => $login,
            'email' => $email,
            'role' => $role,
            'is_blocked' => $is_blocked,
            'id' => $id
        ]);
    }

    public static function exists(\PDO $pdo, string $login, string $email): bool
    {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE login = :login OR email = :email");
        $stmt->execute(['login' => $login, 'email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

    public static function create(\PDO $pdo, string $login, string $email, string $password, string $role = 'user'): bool
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (login, email, password, role) VALUES (:login, :email, :password, :role)");
        return $stmt->execute([
            'login' => $login,
            'email' => $email,
            'password' => $hash,
            'role' => $role
        ]);
    }

    public static function delete(\PDO $pdo, int $id): void
    {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public static function setResetToken(\PDO $pdo, int $id, string $token): void
    {
        $expires = date('Y-m-d H:i:s', time() + 3600);
        $stmt = $pdo->prepare("UPDATE users SET reset_token = :token, reset_token_expires = :expires WHERE id = :id");
        $stmt->execute([
            'token' => $token,
            'expires' => $expires,
            'id' => $id
        ]);
    }

    public static function findByResetToken(\PDO $pdo, string $token): ?array
    {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = :token AND reset_token_expires > NOW()");
        $stmt->execute(['token' => $token]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public static function updatePassword(\PDO $pdo, int $id, string $password): void
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_token_expires = NULL WHERE id = :id");
        $stmt->execute(['password' => $hash, 'id' => $id]);
    }

    public static function clearResetToken(\PDO $pdo, int $id): void
    {
        $stmt = $pdo->prepare("UPDATE users SET reset_token = NULL, reset_token_expires = NULL WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}