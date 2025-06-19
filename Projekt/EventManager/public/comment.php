<?php
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/classes/Event.php';

use classes\Event;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventId = (int)($_POST['event_id'] ?? 0);
    $content = trim($_POST['content'] ?? '');
    $userId = null;
    $guestName = null;
    $parentId = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : 0;
    $isOrganizerReply = 0;

    if (isset($_SESSION['user'])) {
        $userId = (int)$_SESSION['user']['id'];

        if ($parentId > 0) {
            $event = Event::getById($pdo, $eventId);

            if ($event && $event['organizer_id'] == $userId) {
                $isOrganizerReply = 1;
            }
        }
    } else {
        $guestName = 'Gość';
        $parentId = 0;
    }

    if ($eventId > 0 && $content !== '') {
        $stmt = $pdo->prepare("INSERT INTO comments (event_id, user_id, guest_name, content, parent_id, is_organizer_reply) 
                               VALUES (:event_id, :user_id, :guest_name, :content, :parent_id, :is_organizer_reply)");

        $success = $stmt->execute([
            'event_id' => $eventId,
            'user_id' => $userId,
            'guest_name' => $guestName,
            'content' => $content,
            'parent_id' => $parentId > 0 ? $parentId : null,
            'is_organizer_reply' => $isOrganizerReply
        ]);

        if (!$success) {
            error_log("Błąd przy dodawaniu komentarza: " . implode(", ", $stmt->errorInfo()));
        }
    }
}

header("Location: event.php?id=" . urlencode($_POST['event_id']));
exit;