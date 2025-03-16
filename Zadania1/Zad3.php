<?php
$zakres = 9;

$fib[0] = 0;
$fib[1] = 1;

for ($i = 2; $i <= $zakres; $i += 1) {
    $fib[$i] = $fib[$i - 1] + $fib[$i - 2];
}

$liczba = 1;
foreach ($fib as $i => $j) {
    if($j % 2 !== 0){
        echo $liczba. '. '. $j. "\n";
        $liczba++;
    }

}
?>