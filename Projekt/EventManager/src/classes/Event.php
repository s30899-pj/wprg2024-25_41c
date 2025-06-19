<?php

namespace classes;

class Event
{
    public static function getAll(\PDO $pdo) {
        $stmt = $pdo->prepare("SELECT * FROM events ORDER BY start_date DESC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getById(\PDO $pdo, int $id): ?array
    {
        $stmt = $pdo->prepare("SELECT * FROM events WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $event = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $event ?: null;
    }

    public static function count(\PDO $pdo): int
    {
        $stmt = $pdo->query("SELECT COUNT(*) FROM events");
        return (int)$stmt->fetchColumn();
    }

    public static function add(\PDO $pdo, string $title, string $description, string $location, string $startDate, string $endDate, ?string $imageName, int $organizerId, int $capacity = 0): bool
    {
        $stmt = $pdo->prepare("INSERT INTO events (title, description, location, start_date, end_date, image_path, organizer_id, capacity) 
                           VALUES (:title, :description, :location, :start_date, :end_date, :image_path, :organizer_id, :capacity)");

        return $stmt->execute([
            'title' => $title,
            'description' => $description,
            'location' => $location,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'image_path' => $imageName,
            'organizer_id' => $organizerId,
            'capacity' => $capacity
        ]);
    }

    public static function update(\PDO $pdo, int $id, string $title, string $description, string $location, string $startDate, string $endDate, ?string $imageName, int $capacity): void
    {
        $stmt = $pdo->prepare("UPDATE events 
                               SET title = :title, 
                                   description = :description, 
                                   location = :location, 
                                   start_date = :start_date, 
                                   end_date = :end_date, 
                                   image_path = :image_path,
                                   capacity = :capacity
                               WHERE id = :id");
        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'location' => $location,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'image_path' => $imageName,
            'capacity' => $capacity,
            'id' => $id
        ]);
    }

    public static function delete(\PDO $pdo, int $id): void
    {
        $stmt = $pdo->prepare("DELETE FROM events WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public static function getByOrganizer(\PDO $pdo, int $organizerId): array
    {
        $stmt = $pdo->prepare("SELECT * FROM events WHERE organizer_id = :organizer_id");
        $stmt->execute(['organizer_id' => $organizerId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getRegistrationCount(\PDO $pdo, int $eventId): int
    {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM registrations WHERE event_id = :event_id");
        $stmt->execute(['event_id' => $eventId]);
        return (int)$stmt->fetchColumn();
    }
}