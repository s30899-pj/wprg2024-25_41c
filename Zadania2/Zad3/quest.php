<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['num-guests'] = $_POST['num-guests'];
    $_SESSION['first-name'] = $_POST['first-name'];
    $_SESSION['last-name'] = $_POST['last-name'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['address'] = $_POST['address'];
    $_SESSION['credit-card'] = $_POST['credit-card'];
    $_SESSION['check-in-date'] = $_POST['check-in-date'];
    $_SESSION['check-out-date'] = $_POST['check-out-date'];
    $_SESSION['arrival-time'] = $_POST['arrival-time'];
    $_SESSION['extra-bed'] = isset($_POST['extra-bed']) ? 'Tak' : 'Nie';
    $_SESSION['amenities'] = isset($_POST['amenities']) ? implode(", ", $_POST['amenities']) : 'Brak';
}

if (strtotime($_POST['check-out-date']) < strtotime($_POST['check-in-date'])) {
    echo "<p style='color:red; text-align:center;'>Błąd: Data końca pobytu nie może być wcześniejsza niż data początku pobytu.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dane gości</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
<h2>Dane osób – <?= $_POST['num-guests'] ?> gości</h2>
<form action="summary.php" method="POST">
    <?php for ($i = 1; $i <= $_POST['num-guests']; $i++): ?>
        <fieldset>
            <legend>Osoba <?= $i ?></legend>
            <label>Imię: <input type="text" name="guest_firstname_<?= $i ?>" required></label><br>
            <label>Nazwisko: <input type="text" name="guest_lastname_<?= $i ?>" required></label><br>
        </fieldset><br>
    <?php endfor; ?>
    <input type="submit" value="Zobacz podsumowanie">
</form>
</body>
</html>
