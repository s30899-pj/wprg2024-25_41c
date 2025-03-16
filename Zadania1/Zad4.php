<?php
$dane = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";

$tablica = explode(" ", $dane);

for ($j = 0; $j < count($tablica); $j++) {
    for ($k = 0; $k < strlen($tablica[$j]); $k++) {
        if ($tablica[$j][$k] == '.' || $tablica[$j][$k] == ',' || $tablica[$j][$k] == "'") {
            for ($m = $j; $m < count($tablica) - 1; $m++) {
                $tablica[$m] = $tablica[$m + 1];
            }
            unset($tablica[count($tablica) - 1]);
            $tablica = array_values($tablica);
            $j--;
            break;
        }
    }
}

foreach ($tablica as $key => $value) {
    if ($key % 2 == 0) {
        $klucz = $value;
    } else {
        echo "$klucz => $value\n";
    }
}

?>