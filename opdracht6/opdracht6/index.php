<?php
session_start();

// Als gebruiker al ingelogd is, direct doorsturen naar dashboard
if (!empty($_SESSION['gebruiker_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <title>Welkom</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding-top: 100px; }
        a { display: inline-block; margin: 10px; padding: 10px 20px; background: #007BFF; color: white; text-decoration: none; border-radius: 5px; }
        a:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>Welkom bij onze website!</h1>
    <p>Kies wat je wilt doen:</p>
    <a href="register.php">Registreren</a>
    <a href="login.php">Inloggen</a>
</body>
</html>
