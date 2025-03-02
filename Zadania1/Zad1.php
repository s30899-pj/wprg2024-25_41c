<?php
$tablica = array("jabłko", "pomarańcza", "gruszka", "pitaja");

foreach ($tablica as $item) {
    $dlugosc = strlen($item);
    $odwrocony = '';

    for ($i = $dlugosc - 1; $i >= 0; $i--) {
        $odwrocony .= $item[$i];
    }

    
}
?>