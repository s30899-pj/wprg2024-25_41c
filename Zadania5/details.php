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

$id = $_GET['id'];

$sql = "SELECT * FROM samochody WHERE id = $id";
$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_array($result)) {
    echo "<h3>Szczegóły samochodu:</h3>";
    echo "<p><strong>ID:</strong> " . $row['id'] . "</p>";
    echo "<p><strong>Marka:</strong> " . $row['marka'] . "</p>";
    echo "<p><strong>Model:</strong> " . $row['model'] . "</p>";
    echo "<p><strong>Cena:</strong> " . $row['cena'] . "</p>";
    echo "<p><strong>Rok:</strong> " . $row['rok'] . "</p>";
    echo "<p><strong>Opis:</strong> " . $row['opis'] . "</p>";
} else {
    echo "Nie znaleziono samochodu.";
}

echo "<br><a href='index.php'>Powrót do strony głównej</a>";

mysqli_close($conn);
?>

</body>
</html>