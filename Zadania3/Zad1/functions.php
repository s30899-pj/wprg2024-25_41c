<?php
    function addition($firstNumber, $secondNumber) {
        return $firstNumber + $secondNumber;
    }

    function subtraction($firstNumber, $secondNumber) {
        return $firstNumber - $secondNumber;
    }

    function multiplication($firstNumber, $secondNumber) {
        return $firstNumber * $secondNumber;
    }

    function division($firstNumber, $secondNumber) {
        if ($secondNumber == 0) {
            return "Błąd: nie można dzielić przez 0.";
        }
        return $firstNumber / $secondNumber;
    }
?>