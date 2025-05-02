<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $correctLogin = 'admin';
    $correctPassword = 'haslo123';

    if ($login === $correctLogin && $password === $correctPassword) {
        $_SESSION['loggedin'] = true;

        setcookie("last_user", $login, time() + 86400, '/');

        header("Location: index.php");
        exit;
    } else {
        $error = "Nieprawidłowy login lub hasło.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title></head>
    <link rel="stylesheet" href="style.css">
<body>
<h2>Logowanie</h2>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST">
    <label>Login: <input type="text" name="login" required></label><br><br>
    <label>Hasło: <input type="password" name="password" required></label><br><br>
    <input type="submit" value="Zaloguj">
</form>
</body>
</html>