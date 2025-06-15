<?php
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/classes/Event.php';
require_once '../src/classes/Comment.php';
require_once '../src/classes/Registration.php';
require_once '../src/classes/Attachment.php';
require_once '../src/classes/User.php';

use classes\Event;
use classes\Comment;
use classes\Registration;
use classes\Attachment;
use classes\User;

session_start();

$id = (int)($_GET['id'] ?? 0);
$event = Event::getById($pdo, $id);

if (!$event) {
    die("Wydarzenie nie znalezione.");
}

$comments = Comment::getByEventId($pdo, $id);
$attachments = Attachment::getForEvent($pdo, $id);
$isRegistered = isset($_SESSION['user']['id']) ? Registration::isRegistered($pdo, $_SESSION['user']['id'], $id) : false;

$isOrganizerOrAdmin = false;
if (isset($_SESSION['user'])) {
    $isOrganizerOrAdmin = ($_SESSION['user']['role'] === 'admin') ||
        ($_SESSION['user']['role'] === 'organizer' && $event['organizer_id'] == $_SESSION['user']['id']);
}

include 'assets/header.php';
?>

    <h2 class="page-title"><?= htmlspecialchars($event['title'] ?: 'Brak tytułu') ?></h2>

    <div class="event-box">
        <?php if (!empty($event['image_path'])): ?>
            <img src="../uploads/<?= htmlspecialchars($event['image_path']) ?>" alt="<?= htmlspecialchars($event['title']) ?>" class="event-image">
        <?php endif; ?>

        <p><?= nl2br(htmlspecialchars($event['description'] ?: 'Brak opisu')) ?></p>
        <p><strong>Miejsce:</strong> <?= htmlspecialchars($event['location'] ?: '-') ?></p>
        <p><strong>Data:</strong>
            <?php
            $startDate = (!empty($event['start_date']) && strtotime($event['start_date'])) ? date('d.m.Y H:i', strtotime($event['start_date'])) : '-';
            $endDate = (!empty($event['end_date']) && strtotime($event['end_date'])) ? date('d.m.Y H:i', strtotime($event['end_date'])) : '-';
            echo "$startDate – $endDate";
            ?>
        </p>
        <p><strong>Organizator:</strong> <?= htmlspecialchars(User::findById($pdo, $event['organizer_id'])['login'] ?? '-') ?></p>
        <p><strong>Liczba miejsc:</strong>
            <?= $event['capacity'] > 0 ?
                ($event['capacity'] - Registration::countForEvent($pdo, $event['id'])) . '/' . $event['capacity']
                : 'Bez limitu' ?>
        </p>

        <?php if ($event['is_closed']): ?>
            <p class="event-closed">Wydarzenie zakończone</p>
        <?php endif; ?>

        <?php if (!empty($attachments)): ?>
            <h3>Załączniki</h3>
            <div class="attachments">
                <?php foreach ($attachments as $attachment): ?>
                    <div class="attachment">
                        <span><?= htmlspecialchars(basename($attachment['file_path'])) ?></span>
                        <a href="../uploads/<?= htmlspecialchars($attachment['file_path']) ?>" download>Pobierz</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

<?php if (isset($_SESSION['user'])): ?>
    <div style="max-width: 600px; margin: 20px auto;">
        <?php if (!$event['is_closed']): ?>
            <?php if (!$isRegistered): ?>
                <?php if ($event['capacity'] <= 0 || Registration::countForEvent($pdo, $event['id']) < $event['capacity']): ?>
                    <form method="post" action="register_for_event.php">
                        <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                        <input type="submit" value="Zapisz się na wydarzenie" class="button">
                    </form>
                <?php else: ?>
                    <p class="message error">Brak wolnych miejsc na to wydarzenie.</p>
                <?php endif; ?>
            <?php else: ?>
                <p class="message success">Jesteś zarejestrowany na to wydarzenie.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php else: ?>
    <p style="text-align:center;">Zaloguj się, aby zapisać się na wydarzenie.</p>
<?php endif; ?>

<?php if ($isOrganizerOrAdmin): ?>
    <h3 style="text-align:center; margin-top: 30px;">Zarejestrowani uczestnicy</h3>

    <?php
    $stmt = $pdo->prepare("SELECT u.login, u.email, r.registration_date 
                          FROM registrations r
                          JOIN users u ON r.user_id = u.id
                          WHERE r.event_id = ?");
    $stmt->execute([$event['id']]);
    $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <?php if (empty($participants)): ?>
        <p style="text-align:center;">Brak zarejestrowanych uczestników.</p>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; margin: 20px auto; max-width: 800px;">
            <thead>
            <tr>
                <th style="border: 1px solid #ddd; padding: 10px; background: #f1f1f1;">Login</th>
                <th style="border: 1px solid #ddd; padding: 10px; background: #f1f1f1;">Email</th>
                <th style="border: 1px solid #ddd; padding: 10px; background: #f1f1f1;">Data rejestracji</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($participants as $participant): ?>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 10px;"><?= htmlspecialchars($participant['login']) ?></td>
                    <td style="border: 1px solid #ddd; padding: 10px;"><?= htmlspecialchars($participant['email']) ?></td>
                    <td style="border: 1px solid #ddd; padding: 10px;"><?= date('d.m.Y H:i', strtotime($participant['registration_date'])) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
<?php endif; ?>

    <h3 style="text-align:center; margin-top: 30px;">Komentarze</h3>

<?php
function displayComments($comments, $parentId = null, $level = 0, $organizerId) {
    foreach ($comments as $comment) {
        if ($comment['parent_id'] == $parentId) {
            $replyClass = $comment['is_organizer_reply'] ? 'organizer-reply' : '';
            ?>
            <div class="comment <?= $replyClass ?>" style="margin-left: <?= $level * 30 ?>px;">
                <div class="author">
                    <?= htmlspecialchars($comment['guest_name'] ?? $comment['username'] ?? 'Anonim') ?>
                    <?php if ($comment['is_organizer_reply']): ?>
                        <span style="color: #28a745; font-weight: bold;">(Organizator)</span>
                    <?php endif; ?>
                </div>
                <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                <div class="date">
                    <?= date('d.m.Y H:i', strtotime($comment['created_at'])) ?>
                </div>

                <?php if (isset($_SESSION['user'])): ?>
                    <?php if ($_SESSION['user']['role'] === 'organizer' && $organizerId == $_SESSION['user']['id']): ?>
                        <button class="reply-button" data-comment-id="<?= $comment['id'] ?>">Odpowiedz</button>

                        <form method="post" action="comment.php" class="reply-form" id="reply-form-<?= $comment['id'] ?>" style="display: none; margin-top: 10px;">
                            <input type="hidden" name="event_id" value="<?= $_GET['id'] ?>">
                            <input type="hidden" name="parent_id" value="<?= $comment['id'] ?>">
                            <textarea name="content" rows="2" required style="width: 100%; padding: 10px; font-size: 16px; border-radius: 6px; border: 1px solid #ccc;"></textarea>
                            <input type="submit" value="Wyślij odpowiedź" style="margin-top: 10px; padding: 8px 15px; font-size: 14px; background: #28a745; color: white; border: none; border-radius: 6px; cursor: pointer;">
                        </form>
                    <?php endif; ?>
                <?php endif; ?>

                <?php
                displayComments($comments, $comment['id'], $level + 1, $organizerId);
                ?>
            </div>
            <?php
        }
    }
}

if (empty($comments)) {
    echo '<p style="text-align:center; color: #666;">Brak komentarzy do tego wydarzenia.</p>';
} else {
    displayComments($comments, null, 0, $event['organizer_id']);
}
?>

<?php if (isset($_SESSION['user'])): ?>
    <form method="post" action="comment.php" style="max-width: 600px; margin: 20px auto;">
        <input type="hidden" name="event_id" value="<?= (int)$event['id'] ?>">
        <label for="content" style="display:block; font-weight: bold; margin-bottom: 8px;">Dodaj komentarz</label>
        <textarea name="content" id="content" rows="4" required style="width: 100%; padding: 10px; font-size: 16px; border-radius: 6px; border: 1px solid #ccc;"></textarea>
        <input type="submit" value="Wyślij komentarz" style="margin-top: 10px; padding: 12px 20px; font-size: 16px; background: #007bff; color: white; border: none; border-radius: 6px; cursor: pointer;">
    </form>
<?php else: ?>
    <p style="text-align:center; margin-top: 20px;">Zaloguj się, aby dodać komentarz.</p>
<?php endif; ?>

    <script>
        document.querySelectorAll('.reply-button').forEach(button => {
            button.addEventListener('click', function() {
                const formId = 'reply-form-' + this.dataset.commentId;
                const form = document.getElementById(formId);
                form.style.display = form.style.display === 'none' ? 'block' : 'none';
            });
        });
    </script>

    <style>
        .organizer-reply {
            border-left: 3px solid #28a745;
            padding-left: 10px;
            background-color: #f8fff8;
        }

        .reply-button {
            background: #6c757d;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            font-size: 0.9rem;
        }

        .reply-button:hover {
            background: #5a6268;
        }
    </style>

<?php include 'assets/footer.php'; ?>