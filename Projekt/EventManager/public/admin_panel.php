<?php
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/classes/Event.php';
require_once '../src/classes/User.php';
require_once '../src/classes/Auth.php';

use classes\Event;
use classes\User;
use classes\Auth;

session_start();

Auth::requireRole('admin');

if (isset($_GET['delete_event'])) {
    $eventId = (int)$_GET['delete_event'];
    Event::delete($pdo, $eventId);
    header('Location: admin_panel.php');
    exit;
}

if (isset($_GET['delete_user'])) {
    $userId = (int)$_GET['delete_user'];
    User::delete($pdo, $userId);
    header('Location: admin_panel.php');
    exit;
}

$events = Event::getAll($pdo);
$users = User::getAll($pdo);

include 'assets/header.php';
?>

    <h2 class="page-title" style="text-align:center;">Panel administratora</h2>

    <main class="container" style="max-width: 900px; margin: 0 auto;">

        <section style="background: #fff; padding: 25px; margin-bottom: 40px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
            <h3 style="margin-bottom: 20px; text-align: center;">Wydarzenia</h3>
            <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                <thead>
                <tr style="background: #f1f1f1;">
                    <th style="padding: 12px; border: 1px solid #ddd;">ID</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Tytuł</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Data utworzenia</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Opcje</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($events)): ?>
                    <tr><td colspan="4" style="padding: 10px; text-align:center;">Brak wydarzeń.</td></tr>
                <?php else: ?>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= $event['id'] ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($event['title']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= $event['created_at'] ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;">
                                <a href="edit_event.php?id=<?= $event['id'] ?>">Edytuj</a> |
                                <a href="admin_panel.php?delete_event=<?= $event['id'] ?>" onclick="return confirm('Na pewno chcesz usunąć to wydarzenie?')" style="color: red;">Usuń</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </section>

        <section style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
            <h3 style="margin-bottom: 20px; text-align: center;">Użytkownicy</h3>
            <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                <thead>
                <tr style="background: #f1f1f1;">
                    <th style="padding: 12px; border: 1px solid #ddd;">ID</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Login</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Rola</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Status</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Opcje</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($users)): ?>
                    <tr><td colspan="5" style="padding: 10px; text-align:center;">Brak użytkowników.</td></tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= $user['id'] ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($user['login']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($user['role']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd; text-align:center;">
                                <?= ($user['is_blocked'] ?? 0) ? '<span style="color: red;">Zablokowany</span>' : '<span style="color: green;">Aktywny</span>' ?>
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd;">
                                <a href="edit_user.php?id=<?= $user['id'] ?>">Edytuj</a> |
                                <a href="admin_panel.php?delete_user=<?= $user['id'] ?>" onclick="return confirm('Na pewno chcesz usunąć tego użytkownika?')" style="color: red;">Usuń</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </section>

        <section style="background: #fff; padding: 25px; margin-top: 40px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
            <h3 style="margin-bottom: 20px; text-align: center;">Logi systemowe</h3>

            <h4>Logi kontaktowe</h4>
            <pre style="background: #f8f9fa; padding: 15px; border-radius: 6px; max-height: 200px; overflow-y: auto;"><?=
                file_exists('../logs/contact.log') ?
                    htmlspecialchars(file_get_contents('../logs/contact.log')) :
                    'Brak logów kontaktowych'
                ?></pre>

            <h4>Logi resetów hasła</h4>
            <pre style="background: #f8f9fa; padding: 15px; border-radius: 6px; max-height: 200px; overflow-y: auto;"><?=
                file_exists('../logs/password_resets.log') ?
                    htmlspecialchars(file_get_contents('../logs/password_resets.log')) :
                    'Brak logów resetów hasła'
                ?></pre>
        </section>

    </main>

<?php include 'assets/footer.php'; ?>