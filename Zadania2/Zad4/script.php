<?php
function is_prime($num, &$iterations) {
    $iterations = 0;

    if ($num < 2) return false;

    if ($num == 2 || $num == 3) return true;

    if ($num % 2 == 0 || $num % 3 == 0) return false;

    for ($i = 5; $i * $i <= $num; $i += 6) {
        $iterations++;
        if ($num % $i == 0 || $num % ($i + 2) == 0) {
            return false;
        }
    }

    return true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $number = $_POST["number"];

    if (!filter_var($number, FILTER_VALIDATE_INT) || $number < 1) {
        echo "<p style='color:red;'>Podaj poprawną liczbę całkowitą dodatnią.</p>";
        exit;
    }

    $iterations = 0;
    $result = is_prime($number, $iterations);

    if ($result) {
        echo "<p>Liczba <strong>$number</strong> jest liczbą pierwszą.</p>";
    } else {
        echo "<p>Liczba <strong>$number</strong> nie jest liczbą pierwszą.</p>";
    }

    echo "<p>Liczba iteracji: <strong>$iterations</strong></p>";
}
?>
