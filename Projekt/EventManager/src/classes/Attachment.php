<?php

namespace classes;

class Attachment
{
    public static function add(\PDO $pdo, int $eventId, string $filePath): bool
    {
        $stmt = $pdo->prepare("INSERT INTO attachments (event_id, file_path) VALUES (:event_id, :file_path)");
        return $stmt->execute([
            'event_id' => $eventId,
            'file_path' => $filePath
        ]);
    }

    public static function getForEvent(\PDO $pdo, int $eventId): array
    {
        $stmt = $pdo->prepare("SELECT * FROM attachments WHERE event_id = :event_id");
        $stmt->execute(['event_id' => $eventId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function delete(\PDO $pdo, int $id): void
    {
        $stmt = $pdo->prepare("DELETE FROM attachments WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}