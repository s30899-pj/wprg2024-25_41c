<?php
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/classes/Event.php';
require_once '../src/classes/Registration.php';
require_once '../src/classes/Auth.php';

use classes\Event;
use classes\Registration;
use classes\Auth;

session_start();

Auth::requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventId = (int)($_POST['event_id'] ?? 0);

    if ($eventId > 0) {
        $event = Event::getById($pdo, $eventId);
        $userId = $_SESSION['user']['id'];

        if ($event && !$event['is_closed']) {
            $currentRegistrations = Registration::countForEvent($pdo, $eventId);
            if ($event['capacity'] > 0 && $currentRegistrations >= $event['capacity']) {
                $_SESSION['error'] = 'Brak wolnych miejsc na to wydarzenie!';
                header("Location: event.php?id=$eventId");
                exit;
            }

            if (!Registration::isRegistered($pdo, $userId, $eventId)) {
                Registration::register($pdo, $userId, $eventId);
                $_SESSION['success'] = 'Zostałeś zarejestrowany na wydarzenie!';
            } else {
                $_SESSION['error'] = 'Jesteś już zarejestrowany na to wydarzenie!';
            }
        } else {
            $_SESSION['error'] = 'Wydarzenie jest zamknięte!';
        }
    }
}

header("Location: event.php?id=$eventId");
exit;