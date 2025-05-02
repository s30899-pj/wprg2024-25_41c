<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["user-text"]) && !empty(trim($_POST["user-text"]))) {
        $userText = trim($_POST["user-text"]) . PHP_EOL;

        if (file_put_contents('./tekst.txt', $userText, FILE_APPEND | LOCK_EX) !== false) {
            echo "Ciąg został zapisany.";
        } else {
            echo "Wystąpił błąd. Zapis nie został dokonany.";
        }
    } else {
        echo "Nie wprowadzono tekstu.";
    }
}
?>
