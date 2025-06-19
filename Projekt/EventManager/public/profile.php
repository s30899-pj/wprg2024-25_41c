<?php
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/classes/User.php';
require_once '../src/classes/Registration.php';
require_once '../src/classes/Event.php';

use classes\User;
use classes\Registration;
use classes\Event;

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$userId = (int)$_SESSION['user']['id'];
$user = User::findById($pdo, $userId);

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$registrations = Registration::getUserRegistrations($pdo, $userId);
$addedEvents = Event::getByOrganizer($pdo, $userId);

include 'assets/header.php';
?>

    <h2 class="page-title">Profil użytkownika</h2>

    <div class="profile-box">
        <p><strong>Login:</strong> <?= htmlspecialchars($user['login']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? '-') ?></p>
        <p><strong>Rola:</strong> <?= htmlspecialchars($user['role']) ?></p>
        <p><strong>Data rejestracji:</strong> <?= htmlspecialchars($user['created_at'] ?? '-') ?></p>
    </div>

<?php if (!empty($registrations)): ?>
    <h3 style="text-align:center; margin-top: 30px;">Moje rejestracje</h3>
    <div class="registration-list">
        <?php foreach ($registrations as $registration): ?>
            <div class="registration-item">
                <h4>
                    <a href="event.php?id=<?= $registration['event_id'] ?>"><?= htmlspecialchars($registration['event_title']) ?></a>
                </h4>
                <p><strong>Data
                        rejestracji:</strong> <?= date('d.m.Y H:i', strtotime($registration['registration_date'])) ?>
                </p>
                <p><strong>Data wydarzenia:</strong> <?= date('d.m.Y H:i', strtotime($registration['start_date'])) ?>
                    - <?= date('d.m.Y H:i', strtotime($registration['end_date'])) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php if (!empty($addedEvents)): ?>
    <h3 style="text-align:center; margin-top: 30px;">Moje wydarzenia</h3>
    <div class="my-events">
        <?php foreach ($addedEvents as $addedEvent): ?>
            <?php $count = Event::getRegistrationCount($pdo, $addedEvent['id']); ?>
            <div class="my-event-item">
                <h4>
                    <a href="event.php?id=<?= $addedEvent['id'] ?>"><?= htmlspecialchars($addedEvent['title']) ?></a>
                </h4>
                <p><strong>Zajętych miejsc:</strong> <?= $count ?></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
    <div style="text-align: center; margin-top: 20px;">
        <a href="edit_profile.php" class="button">Edytuj profil</a>
    </div>
<?php include 'assets/footer.php'; ?>