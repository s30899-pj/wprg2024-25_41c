<?php

namespace classes;

class Registration
{
    public static function register(\PDO $pdo, int $userId, int $eventId): bool
    {
        $stmt = $pdo->prepare("INSERT INTO registrations (user_id, event_id) VALUES (:user_id, :event_id)");
        return $stmt->execute([
            'user_id' => $userId,
            'event_id' => $eventId
        ]);
    }

    public static function isRegistered(\PDO $pdo, int $userId, int $eventId): bool
    {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM registrations WHERE user_id = :user_id AND event_id = :event_id");
        $stmt->execute(['user_id' => $userId, 'event_id' => $eventId]);
        return $stmt->fetchColumn() > 0;
    }

    public static function countForEvent(\PDO $pdo, int $eventId): int
    {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM registrations WHERE event_id = :event_id");
        $stmt->execute(['event_id' => $eventId]);
        return (int)$stmt->fetchColumn();
    }

    public static function getUserRegistrations(\PDO $pdo, int $userId): array
    {
        $stmt = $pdo->prepare("SELECT r.*, e.title as event_title, e.start_date, e.end_date 
                               FROM registrations r 
                               JOIN events e ON r.event_id = e.id 
                               WHERE r.user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}