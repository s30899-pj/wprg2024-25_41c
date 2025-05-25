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

$sql = "SELECT * FROM samochody ORDER BY rok DESC";
$result = mysqli_query($conn, $sql);

echo "<h3>Wszystkie samochody:</h3>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Marka</th><th>Model</th><th>Cena</th></tr>";

while ($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td><a href='details.php?id=" . $row['id'] . "'>" . $row['id'] . "</a></td>";
    echo "<td>" . $row['marka'] . "</td>";
    echo "<td>" . $row['model'] . "</td>";
    echo "<td>" . $row['cena'] . "</td>";
    echo "</tr>";
}

echo "</table>";

mysqli_close($conn);
?>

</body>
</html>