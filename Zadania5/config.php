<?php
$host = '127.0.0.1';
$user = 'root';
$pass = 'root';
$dbname = 'mojaBaza';

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Błąd połączenia: " . mysqli_connect_error());
}
?>