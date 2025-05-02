<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $plik = 'rezerwacje.csv';
    $isNewFile = !file_exists($plik);

    $numGuests = (int)$_POST['num-guests'];

    $guestNames = [];
    for ($i = 1; $i <= 4; $i++) {
        if ($i <= $numGuests) {
            $first = $_POST["guest_firstname_$i"] ?? '';
            $last = $_POST["guest_lastname_$i"] ?? '';
            $guestNames[] = trim("$first $last");
        } else {
            $guestNames[] = "-";
        }
    }

    $data = [
        $_POST['num-guests'],
        $_POST['first-name'],
        $_POST['last-name'],
        $_POST['email'],
        $_POST['address'],
        str_repeat("*", 12) . substr($_POST['credit-card'], -4),
        $_POST['check-in-date'],
        $_POST['check-out-date'],
        isset($_POST['arrival-time']) && $_POST['arrival-time'] !== '' ? $_POST['arrival-time'] : 'Brak',
        isset($_POST['extra-bed']) ? 'Tak' : 'Nie',
        isset($_POST['amenities']) ? implode(", ", $_POST['amenities']) : 'Brak'
    ];

    $headers = [
        "Liczba osób", "Imię", "Nazwisko", "E-mail", "Adres",
        "Numer karty", "Data przyjazdu", "Data wyjazdu",
        "Godzina przyjazdu", "Łóżko dla dziecka", "Udogodnienia",
        "Gość 1", "Gość 2", "Gość 3", "Gość 4"
    ];

    $data = array_merge($data, $guestNames);

    $file = fopen($plik, 'a');

    if ($isNewFile) {
        fputcsv($file, $headers, ";", '"', "\\");
    }

    fputcsv($file, $data, ';', '"', "\\");
    fclose($file);

    echo "<p style='text-align:center;'>Pomyślnie dodano rezerwację.</p>";
    echo "<p style='text-align:center;'><a href='index.php'>Wróć</a></p>";
    exit;
}
?>