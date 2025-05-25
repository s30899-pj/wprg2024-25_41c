<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Portal Samochodowy</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
include 'config.php';
include 'menu.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $marka = $_POST['marka'];
    $model = $_POST['model'];
    $cena = $_POST['cena'];
    $rok = $_POST['rok'];
    $opis = $_POST['opis'];

    $sql = "INSERT INTO samochody (marka, model, cena, rok, opis)
            VALUES ('$marka', '$model', '$cena', '$rok', '$opis')";

    if (mysqli_query($conn, $sql)) {
        echo "Samochód dodany pomyślnie!";
    } else {
        echo "Błąd: " . mysqli_error($conn);
    }
}
?>

<h3>Dodaj nowy samochód:</h3>
<form method="POST" action="">
    Marka: <input type="text" name="marka"><br>
    Model: <input type="text" name="model"><br>
    Cena: <input type="number" name="cena"><br>
    Rok: <input type="number" name="rok"><br>
    Opis: <textarea name="opis"></textarea><br>
    <input type="submit" value="Dodaj">
</form>

<?php
mysqli_close($conn);
?>

</body>
</html>