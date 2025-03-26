<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numGuests = $_SESSION['num-guests'];
    $firstName = $_SESSION['first-name'];
    $lastName = $_SESSION['last-name'];
    $email = $_SESSION['email'];
    $address = $_SESSION['address'];
    $creditCard = $_SESSION['credit-card'];
    $checkInDate = date("d.m.Y", strtotime($_SESSION['check-in-date']));
    $checkOutDate = date("d.m.Y", strtotime($_SESSION['check-out-date']));
    $arrivalTime = $_SESSION['arrival-time'];
    $extraBed = $_SESSION['extra-bed'];
    $amenities = $_SESSION['amenities'];
}

$maskedCard = str_repeat("*", 12) . substr($creditCard, -4);
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
    <p><strong>Imię rezerwującego:</strong> <?= $firstName ?></p>
    <p><strong>Nazwisko:</strong> <?= $lastName ?></p>
    <p><strong>Email:</strong> <?= $email ?></p>
    <p><strong>Karta kredytowa:</strong> <?= $maskedCard ?></p>
    <p><strong>Liczba osób:</strong> <?= $numGuests ?></p>
    <p><strong>Data pobytu:</strong> <?= $checkInDate ?> – <?= $checkOutDate ?></p>
    <p><strong>Godzina przyjazdu:</strong> <?= $arrivalTime ?? 'Nie określono' ?></p>
    <p><strong>Łóżko dla dziecka:</strong> <?= $extraBed ?></p>
    <p><strong>Udogodnienia:</strong> <?= $amenities ?></p>

    <h3>Dane gości:</h3>
    <ul>
        <?php
        for ($i = 1; $i <= $numGuests; $i++) {
            $fname = isset($_POST["guest_firstname_$i"]) ? $_POST["guest_firstname_$i"] : '';
            $lname = isset($_POST["guest_lastname_$i"]) ? $_POST["guest_lastname_$i"] : '';
            echo "<li><strong>Osoba $i:</strong> $fname $lname</li>";
        }
        ?>
    </ul>
</div>

</body>
</html>
