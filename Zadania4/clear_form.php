<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

$fields = ['num-guests', 'first-name', 'last-name', 'email', 'address', 'credit-card',
    'check-in-date', 'check-out-date', 'arrival-time', 'extra-bed', 'amenities'];

foreach ($fields as $field) {
    setcookie($field, '', time() - 3600, '/');
}

header("Location: index.php");
exit;