<?php
session_start();

if (isset($_COOKIE['remember'])) {
    setcookie('remember', '', time() - 3600, '/');
}

session_unset();
session_destroy();
header('Location: login.php');
exit;