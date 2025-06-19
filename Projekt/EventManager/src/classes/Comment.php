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
}