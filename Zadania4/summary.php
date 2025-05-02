<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numGuests = $_POST['num-guests'];
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $creditCard = $_POST['credit-card'];
    $checkInDate = date("d.m.Y", strtotime($_POST['check-in-date']));
    $checkOutDate = date("d.m.Y", strtotime($_POST['check-out-date']));
    $arrivalTime = $_POST['arrival-time'] ?? 'Nie określono';
    $extraBed = isset($_POST['extra-bed']) ? 'Tak' : 'Nie';
    $amenities = isset($_POST['amenities']) ? implode(", ", $_POST['amenities']) : 'Brak';

    $maskedCard = str_repeat("*", 12) . substr($creditCard, -4);
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Podsumowanie rezerwacji</title>
    <link rel="stylesheet" href="style3.css">
</head>
<body>
<div class="summary">
    <h2>Podsumowanie rezerwacji</h2>
    <p><strong>Imię rezerwującego:</strong> <?= htmlspecialchars($firstName) ?></p>
    <p><strong>Nazwisko:</strong> <?= htmlspecialchars($lastName) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
    <p><strong>Adres:</strong> <?= htmlspecialchars($address) ?></p>
    <p><strong>Karta kredytowa:</strong> <?= $maskedCard ?></p>
    <p><strong>Liczba osób:</strong> <?= $numGuests ?></p>
    <p><strong>Data pobytu:</strong> <?= $checkInDate ?> – <?= $checkOutDate ?></p>
    <p><strong>Godzina przyjazdu:</strong> <?= $arrivalTime ?></p>
    <p><strong>Łóżko dla dziecka:</strong> <?= $extraBed ?></p>
    <p><strong>Udogodnienia:</strong> <?= htmlspecialchars($amenities) ?></p>

    <h3>Dane gości:</h3>
    <ul>
        <?php
        for ($i = 1; $i <= $numGuests; $i++) {
            $fname = htmlspecialchars($_POST["guest_firstname_$i"]);
            $lname = htmlspecialchars($_POST["guest_lastname_$i"]);
            echo "<li><strong>Osoba $i:</strong> $fname $lname</li>";
        }
        ?>
    </ul>
</div>
</body>
</html>