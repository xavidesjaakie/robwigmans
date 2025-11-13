<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "film project";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("Verbinding mislukt: " . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acteurnaam = $_POST['acteurnaam'];
    $stmt = $conn->prepare("INSERT INTO acteur (acteurnaam) VALUES (?)");
    $stmt->bind_param("s", $acteurnaam);
    $stmt->execute();
    $stmt->close();
    echo "<p style='color:lightgreen;text-align:center;'>Acteur succesvol toegevoegd!</p>";
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Acteur Beheer</title>
<style>
body { background:#5a46a0; color:white; font-family:Arial; margin:0; }
.navbar { display:flex; justify-content:space-between; align-items:center;
    background:#7a63d1; padding:10px 30px; border-radius:0 0 10px 10px; box-shadow:0 2px 6px rgba(0,0,0,0.3);}
.nav-buttons { display:flex; gap:10px; }
.nav-buttons a { background:#249ea4; color:white; padding:8px 16px; border-radius:10px; text-decoration:none; font-weight:bold; transition:0.2s; }
.nav-buttons a:hover { background:#1e7f82; }
form { background:#150078; padding:20px; border-radius:10px; width:300px; margin:40px auto; }
input, button { width:100%; margin:8px 0; padding:8px; border-radius:5px; border:none; }
button { background:#249ea4; color:white; font-weight:bold; cursor:pointer; }
</style>
</head>
<body>

<div class="navbar">
    <h2>🎬 Filmproject Rob</h2>
    <div class="nav-buttons">
        <a href="filmopdracht project rob.php">Home</a>
        <a href="FilmBeheer.php">FilmBeheer</a>
        <a href="ActeurBeheer.php">ActeurBeheer</a>
        <a href="MijnInfo.php">Mijn Info</a>
    </div>
</div>

<h2 style="text-align:center;">Acteur toevoegen</h2>
<form method="POST">
    <label>Naam acteur:</label>
    <input type="text" name="acteurnaam" required>
    <button type="submit">Opslaan</button>
</form>
</body>
</html>
