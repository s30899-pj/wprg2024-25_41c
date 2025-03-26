<?php
    $firstNumber = $_GET['first-number'];
    $operation = $_GET['operations'];
    $secondNumber = $_GET['second-number'];

    $result = 0;
    switch ($operation) {
        case "+":
            $result = $firstNumber + $secondNumber;
            break;
        case "-":
            $result = $firstNumber - $secondNumber;
            break;
        case "*":
            $result = $firstNumber * $secondNumber;
            break;
        case "/":
            if ($secondNumber == 0) {
                echo "Błąd. Nie można dzielić przez 0.";
                return;
            } else {
                $result = $firstNumber / $secondNumber;
            }
            break;
    }

    echo "Wynik: $result";
?>