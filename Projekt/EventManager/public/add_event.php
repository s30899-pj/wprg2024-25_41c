<?php
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/classes/Event.php';
require_once '../src/classes/Auth.php';

use classes\Event;
use classes\Auth;

session_start();

Auth::requireRoles(['admin', 'organizer']);

$errors = [];
$title = '';
$description = '';
$location = '';
$start_date = '';
$end_date = '';
$capacity = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $capacity = (int)($_POST['capacity'] ?? 0);

    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image']['type'], $allowedTypes) && $_FILES['image']['size'] <= 2 * 1024 * 1024) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], "uploads/$imageName");
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
        if (Event::add($pdo, $title, $description, $location, $start_date, $end_date, $imageName, $_SESSION['user']['id'], $capacity)) {
            $_SESSION['success'] = 'Wydarzenie zostało dodane!';
            header('Location: events.php');
            exit;
        } else {
            $errors[] = 'Wystąpił błąd podczas dodawania wydarzenia.';
        }
    }
}

include 'assets/header.php';
?>

    <h2 class="page-title">Dodaj wydarzenie</h2>

<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $error): ?>
            <li class="message error"><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

    <form action="add_event.php" method="post" enctype="multipart/form-data">
        <label for="title">Tytuł wydarzenia</label>
        <input type="text" name="title" id="title" value="<?= htmlspecialchars($title) ?>" required>

        <label for="description">Opis</label>
        <textarea name="description" id="description" required><?= htmlspecialchars($description) ?></textarea>

        <label for="location">Miejsce</label>
        <input type="text" name="location" id="location" value="<?= htmlspecialchars($location) ?>" required>

        <label for="start_date">Data rozpoczęcia</label>
        <input type="datetime-local" name="start_date" id="start_date" value="<?= htmlspecialchars($start_date) ?>" required>

        <label for="end_date">Data zakończenia</label>
        <input type="datetime-local" name="end_date" id="end_date" value="<?= htmlspecialchars($end_date) ?>" required>

        <label for="capacity">Liczba miejsc (0 = bez limitu)</label>
        <input type="number" name="capacity" id="capacity" min="0" value="<?= $capacity ?>">

        <label for="image">Obrazek (opcjonalnie)</label>
        <div class="file-input-container">
            <label class="file-button">
                <span class="file-icon">📁</span> Wybierz plik
                <input type="file" class="file-input" id="image" name="image" accept="image/*">
            </label>
            <span class="file-name" id="file-name">Nie wybrano pliku</span>
        </div>

        <input type="submit" value="Dodaj wydarzenie">
    </form>
    <script>
        document.getElementById('image').addEventListener('change', function () {
            const fileName = this.files.length ? this.files[0].name : 'Nie wybrano pliku';
            document.getElementById('file-name').textContent = fileName;
        });
    </script>

<?php include 'assets/footer.php'; ?>