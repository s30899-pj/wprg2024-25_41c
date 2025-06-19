<?php
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/classes/Event.php';

use classes\Event;

session_start();

$events = Event::getAll($pdo);

include 'assets/header.php';
?>

    <h2 class="page-title">Lista wydarzeń</h2>

<?php if (empty($events)): ?>
    <p class="no-events">Brak dostępnych wydarzeń.</p>
<?php else: ?>
    <div class="events-grid">
        <?php foreach ($events as $event): ?>
            <div class="event-box">
                <?php if (!empty($event['image_path'])): ?>
                    <img src="uploads/<?= htmlspecialchars($event['image_path']) ?>" alt="<?= htmlspecialchars($event['title']) ?>" class="event-image">
                <?php endif; ?>
                <h3>
                    <a href="event.php?id=<?= $event['id'] ?>">
                        <?= htmlspecialchars($event['title'] ?: 'Brak tytułu') ?>
                    </a>
                </h3>
                <p><strong>Miejsce:</strong> <?= htmlspecialchars($event['location'] ?: '-') ?></p>
                <p><strong>Data:</strong>
                    <?php
                    $startDate = (!empty($event['start_date']) && strtotime($event['start_date'])) ? date('d.m.Y H:i', strtotime($event['start_date'])) : '-';
                    $endDate = (!empty($event['end_date']) && strtotime($event['end_date'])) ? date('d.m.Y H:i', strtotime($event['end_date'])) : '-';
                    echo "$startDate – $endDate";
                    ?>
                </p>
                <?php if ($event['is_closed']): ?>
                    <p class="event-closed">Wydarzenie zakończone</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'assets/footer.php'; ?>