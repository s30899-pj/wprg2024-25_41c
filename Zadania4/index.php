<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    echo "<p style='color:red; text-align:center;'>Brak dostępu – musisz być zalogowany, aby zarezerwować hotel.</p>";
    echo "<p style='text-align:center;'>Dostęp do formularza rezerwacji mają tylko zalogowani użytkownicy z aktywną sesją.</p>";
    echo "<p style='text-align:center;'><a href='login.php'>Zaloguj się</a></p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if (is_array($value)) {
            setcookie($key, json_encode($value), time() + 86400, '/');
        } else {
            setcookie($key, $value, time() + 86400, '/');
        }
    }
    header("Location: index.php");
    exit;
}

$formData = [];
$fields = ['num-guests', 'first-name', 'last-name', 'email', 'address', 'credit-card',
    'check-in-date', 'check-out-date', 'arrival-time', 'extra-bed', 'amenities'];

foreach ($fields as $field) {
    if (isset($_COOKIE[$field])) {
        $formData[$field] = $field === 'amenities' ? json_decode($_COOKIE[$field], true) : $_COOKIE[$field];
    } else {
        $formData[$field] = '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezerwacja hotelu</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>

<?php if (isset($_COOKIE['last_user'])): ?>
    <p style="font-weight:bold; text-align:center;">Witaj ponownie, <?= htmlspecialchars($_COOKIE['last_user']) ?>!</p>
<?php endif; ?>

<form action="logout.php" method="post" style="text-align:center; margin-bottom: 10px;">
    <button type="submit">Wyloguj</button>
</form>

<form action="guest.php" method="post">
    <label for="num-guests">Liczba osób:</label>
    <select id="num-guests" name="num-guests" required>
        <?php foreach ([1, 2, 3, 4] as $num): ?>
            <option value="<?= $num ?>" <?= $formData['num-guests'] == $num ? 'selected' : '' ?>><?= $num ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="first-name">Imię:</label>
    <input type="text" id="first-name" name="first-name" required
           value="<?= htmlspecialchars($formData['first-name']) ?>"><br><br>

    <label for="last-name">Nazwisko:</label>
    <input type="text" id="last-name" name="last-name" required
           value="<?= htmlspecialchars($formData['last-name']) ?>"><br><br>

    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" required
           value="<?= htmlspecialchars($formData['email']) ?>"><br><br>

    <label for="address">Adres:</label>
    <textarea id="address" name="address"><?= htmlspecialchars($formData['address']) ?></textarea><br><br>

    <label for="credit-card">Numer karty kredytowej:</label>
    <input type="text" id="credit-card" name="credit-card" pattern="\d{16}" required
           value="<?= htmlspecialchars($formData['credit-card']) ?>"><br><br>

    <label for="check-in-date">Data początku pobytu:</label>
    <input type="date" id="check-in-date" name="check-in-date" required
           value="<?= $formData['check-in-date'] ?>"><br><br>

    <label for="check-out-date">Data końca pobytu:</label>
    <input type="date" id="check-out-date" name="check-out-date" required
           value="<?= $formData['check-out-date'] ?>"><br><br>

    <label for="arrival-time">Godzina przyjazdu:</label>
    <input type="time" id="arrival-time" name="arrival-time" value="<?= $formData['arrival-time'] ?>"><br><br>

    <label for="extra-bed">Łóżka dla dziecka:</label>
    <input type="checkbox" id="extra-bed" name="extra-bed" <?= !empty($formData['extra-bed']) ? 'checked' : '' ?>><br><br>

    <label for="amenities">Wybierz udogodnienia:</label>
    <?php $selected = is_array($formData['amenities']) ? $formData['amenities'] : []; ?>
    <select id="amenities" name="amenities[]" multiple>
        <option value="klimatyzacja" <?= in_array("klimatyzacja", $selected) ? 'selected' : '' ?>>Klimatyzacja
        </option>
        <option value="popielniczka" <?= in_array("popielniczka", $selected) ? 'selected' : '' ?>>Popielniczka
        </option>
        <option value="sauna" <?= in_array("sauna", $selected) ? 'selected' : '' ?>>Sauna</option>
    </select><br><br>

    <input type="submit" value="Zarezerwuj">
</form>

<form action="clear_form.php" method="post" style="text-align:center; margin-top:10px;">
    <button type="submit">Wyczyść formularz</button>
</form>

</body>
</html>