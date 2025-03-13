<?php
$tablica = array("jablko", "pomarancza", "gruszka", "pitaja");

foreach ($tablica as $item) {
    $dlugosc = 0;
    $odwrocony = '';

//    while (@$item[$dlugosc]) {
//        $dlugosc++;
//    }

    for ($dlugosc = 0; ($item[$dlugosc] ?? false) !== false; $dlugosc++);

    if($item[0] == "p")
        echo 'Owoc zaczyna się na litere p: ';

    for ($i = $dlugosc - 1; $i >= 0; $i--) {
        $odwrocony .= $item[$i];
    }

    echo $odwrocony. "\n";
}
?>