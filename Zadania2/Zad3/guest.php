<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $checkIn = $_POST['check-in-date'];
    $checkOut = $_POST['check-out-date'];

    if (strtotime($checkOut) < strtotime($checkIn)) {
        echo "<p style='color:red; text-align:center;'>Błąd: Data końca pobytu nie może być wcześniejsza niż początek pobytu.</p>";
        echo "<p style='text-align:center;'><a href='index.php'>Wróć</a></p>";
        exit;
    }

    $hiddenFields = '';
    foreach ($_POST as $key => $value) {
        if (is_array($value)) {
            foreach ($value as $v) {
                $hiddenFields .= "<input type='hidden' name='{$key}[]' value='" . htmlspecialchars($v) . "'>\n";
            }
        } else {
            $hiddenFields .= "<input type='hidden' name='$key' value='" . htmlspecialchars($value) . "'>\n";
        }
    }

    $numGuests = (int)$_POST['num-guests'];
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
<h2>Dane osób – <?= $numGuests ?> gości</h2>
<form action="summary.php" method="POST">
    <?= $hiddenFields ?>
    <?php for ($i = 1; $i <= $numGuests; $i++): ?>
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