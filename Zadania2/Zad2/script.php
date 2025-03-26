<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numGuests = $_POST['num-guests'];
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $creditCard = $_POST['credit-card'];
    $checkInDate = date("d.m.Y", strtotime($_POST['check-in-date']));
    $checkOutDate = date("d.m.Y", strtotime($_POST['check-out-date']));
    $arrivalTime = $_POST['arrival-time'];
    $extraBed = isset($_POST['extra-bed']) ? 'Tak' : 'Nie';
    $amenities = isset($_POST['amenities']) ? implode(", ", $_POST['amenities']) : 'Brak';

    $maskedCard = str_repeat("*", 12) . substr($creditCard, -4);

    if (strtotime($checkOutDate) < strtotime($checkInDate)) {
        echo "<p style='color:red; text-align:center;'>Błąd: Data końca pobytu nie może być wcześniejsza niż data początku pobytu.</p>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Podsumowanie rezerwacji</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .summary {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .summary h2 {
            margin-bottom: 20px;
        }
        .summary p {
            margin: 8px 0;
        }
    </style>
</head>
<body>
<div class="summary">
    <h2>Podsumowanie rezerwacji</h2>
    <p><strong>Imię:</strong> <?=$firstName ?></p>
    <p><strong>Nazwisko:</strong> <?=$lastName ?></p>
    <p><strong>Email:</strong> <?=$email ?></p>
    <p><strong>Karta kredytowa:</strong> <?=$maskedCard ?></p>
    <p><strong>Liczba osób:</strong> <?=$numGuests ?></p>
    <p><strong>Data pobytu:</strong> <?=$checkInDate ?></p>
    <p><strong>Data wyjazdu:</strong> <?=$checkOutDate ?></p>
    <p><strong>Godzina przyjazdu:</strong> <?=$arrivalTime ?></p>
    <p><strong>Łóżko dla dziecka:</strong> <?=$extraBed ?></p>
    <p><strong>Udogodnienia:</strong> <?=$amenities ?></p>
</div>
</body>
</html>

