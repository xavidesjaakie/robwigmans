<?php
session_start();

// Alleen toegankelijk als ingelogd
if (empty($_SESSION['gebruiker_id'])) {
    header('Location: index.php');
    exit;
}

$gebruikersnaam = $_SESSION['gebruikersnaam'];
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding-top: 100px; }
        a { display: inline-block; margin: 10px; padding: 10px 20px; background: #DC3545; color: white; text-decoration: none; border-radius: 5px; }
        a:hover { background: #b02a37; }
    </style>
</head>
<body>
    <h1>Welkom, <?=htmlspecialchars($gebruikersnaam)?>!</h1>
    <p>Je bent ingelogd.</p>
    <a href="logout.php">Uitloggen</a>
</body>
</html>
