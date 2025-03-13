<?php
$zakres = 20;

echo "Liczby pierwsze: ";
echo '2 ';

for ($i = 3; $i <= $zakres; $i += 2) {

    $pierwsza = true;
    for ($j = 3; $j <= sqrt($i); $j += 2) {
        if($i % $j == 0) {
            $pierwsza = false;
            break;
        }
    }

    if($pierwsza)
        echo $i. ' ';

}
?>