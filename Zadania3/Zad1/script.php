<?php
include("functions.php");
$firstNumber = $_GET['first-number'];
$secondNumber = $_GET['second-number'];
$operation = $_GET['operations'];

$result = 0;
switch ($operation) {
    case "+":
        $result = addition($firstNumber, $secondNumber);
        break;
    case "-":
        $result = subtraction($firstNumber, $secondNumber);
        break;
    case "*":
        $result = multiplication($firstNumber, $secondNumber);
        break;
    case "/":
        $result = division($firstNumber, $secondNumber);
        break;
}

echo "Wynik: $result";
?>