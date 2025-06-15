<?php

namespace classes;

class Comment
{
    public static function getByEventId(\PDO $pdo, int $eventId): array
    {
        $stmt = $pdo->prepare("SELECT c.*, u.login as username 
                               FROM comments c 
                               LEFT JOIN users u ON c.user_id = u.id 
                               WHERE event_id = :event_id 
                               ORDER BY created_at DESC");
        $stmt->execute(['event_id' => $eventId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function count(\PDO $pdo): int
    {
        $stmt = $pdo->query("SELECT COUNT(*) FROM comments");
        return (int)$stmt->fetchColumn();
    }

    public static function add(\PDO $pdo, int $eventId, ?int $userId, ?string $guestName, string $content): bool
    {
        $stmt = $pdo->prepare("INSERT INTO comments (event_id, user_id, guest_name, content) 
                           VALUES (:event_id, :user_id, :guest_name, :content)");

        return $stmt->execute([
            'event_id' => $eventId,
            'user_id' => $userId,
            'guest_name' => $guestName,
            'content' => $content
        ]);
    }

    public static function delete(\PDO $pdo, int $id): void
    {
        $stmt = $pdo->prepare("DELETE FROM comments WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}