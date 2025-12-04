<?php
require_once 'functions.php';
require_login(); // redirect naar login.php als niet ingelogd
$naam = huidige_gebruiker_naam();
?>
<!doctype html>
<html lang="nl">
<head><meta charset="utf-8"><title>Dashboard</title></head>
<body>
  <h1>Welkom, <?=htmlspecialchars($naam)?>!</h1>
  <p>Dit is een beveiligde pagina.</p>
  <p><a href="logout.php">Uitloggen</a></p>
</body>
</html>
