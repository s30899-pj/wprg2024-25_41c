<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Wczytane dane</title>
    <style>
        body { padding: 40px; background-color: #f9f9f9; }
        table {
            border-collapse: collapse;
            margin: auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px 14px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
<h2 style="text-align:center;">Zapisane rezerwacje</h2>

<?php
$plik = 'rezerwacje.csv';
if (file_exists($plik)) {
    echo "<table>";
    if (($handle = fopen($plik, 'r')) !== false) {
        while (($row = fgetcsv($handle, 1000, ';', '"', "\\")) !== false) {
            echo "<tr>";
            foreach ($row as $cell) {
                echo "<td>" . htmlspecialchars($cell) . "</td>";
            }
            echo "</tr>";
        }
        fclose($handle);
    }
    echo "</table>";
} else {
    echo "<p style='text-align:center;'>Brak zapisanych danych.</p>";
}
?>
</body>
</html>