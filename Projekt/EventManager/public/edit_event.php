<?php
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/classes/Event.php';
require_once '../src/classes/Auth.php';

use classes\Event;
use classes\Auth;

session_start();

Auth::requireRoles(['admin', 'organizer']);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: admin_panel.php');
    exit;
}

$errors = [];
$event = Event::getById($pdo, $id);

if (!$event) {
    die("Wydarzenie nie znalezione.");
}

if ($_SESSION['user']['role'] !== 'admin' && $event['organizer_id'] !== $_SESSION['user']['id']) {
    header('Location: events.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $capacity = (int)($_POST['capacity'] ?? 0);

    $imageName = $event['image_path'];

    if (!empty($_FILES['image']['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image']['type'], $allowedTypes) && $_FILES['image']['size'] <= 2 * 1024 * 1024) {
            if ($imageName && file_exists("../uploads/$imageName")) {
                unlink("../uploads/$imageName");
            }

            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/$imageName");
        } else {
            $errors[] = 'Nieprawidłowy typ lub rozmiar pliku (max 2MB, jpg/png/gif).';
        }
    }

    if (empty($title)) {
        $errors[] = 'Tytuł jest wymagany.';
    }
    if (empty($description)) {
        $errors[] = 'Opis jest wymagany.';
    }
    if (empty($location)) {
        $errors[] = 'Miejsce jest wymagane.';
    }
    if (empty($start_date)) {
        $errors[] = 'Data rozpoczęcia jest wymagana.';
    }
    if (empty($end_date)) {
        $errors[] = 'Data zakończenia jest wymagana.';
    }
    if ($capacity < 0) {
        $errors[] = 'Liczba miejsc nie może być ujemna.';
    }

    if (empty($errors)) {
        Event::update($pdo, $id, $title, $description, $location, $start_date, $end_date, $imageName, $capacity);
        $_SESSION['success'] = 'Wydarzenie zostało zaktualizowane!';
        header('Location: events.php');
        exit;
    }
}

include 'assets/header.php';
?>

    <h2>Edytuj wydarzenie</h2>

<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $error): ?>
            <li class="message error"><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

    <form method="post" action="edit_event.php?id=<?= $id ?>" enctype="multipart/form-data">
        <label for="title">Tytuł:</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($event['title']) ?>" required>

        <label for="description">Opis:</label>
        <textarea id="description" name="description" rows="5" required><?= htmlspecialchars($event['description']) ?></textarea>

        <label for="location">Miejsce:</label>
        <input type="text" id="location" name="location" value="<?= htmlspecialchars($event['location']) ?>" required>

        <label for="start_date">Data rozpoczęcia:</label>
        <input type="datetime-local" id="start_date" name="start_date" value="<?= date('Y-m-d\TH:i', strtotime($event['start_date'])) ?>" required>

        <label for="end_date">Data zakończenia:</label>
        <input type="datetime-local" id="end_date" name="end_date" value="<?= date('Y-m-d\TH:i', strtotime($event['end_date'])) ?>" required>

        <label for="capacity">Liczba miejsc (0 = bez limitu):</label>
        <input type="number" id="capacity" name="capacity" min="0" value="<?= $event['capacity'] ?>">

        <label for="image">Obrazek (opcjonalnie)</label>
        <div class="file-input-container">
            <div class="file-button-container">
                <label class="file-button">
                    <span class="file-icon">📁</span> Wybierz plik
                    <input type="file" class="file-input" id="image" name="image" accept="image/*">
                </label>
            </div>
            <span class="file-name" id="file-name">Nie wybrano pliku</span>
        </div>
        <?php if ($event['image_path']): ?>
            <p>Aktualny obrazek: <?= htmlspecialchars($event['image_path']) ?></p>
        <?php endif; ?>

        <input type="submit" value="Zapisz zmiany">
    </form>

<?php include 'assets/footer.php'; ?>