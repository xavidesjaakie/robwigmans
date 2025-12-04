<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "film project";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("Verbinding mislukt: " . $conn->connect_error);

$acteur_id = 1; // pas aan naar jouw ID
$sql = "
    SELECT a.acteurnaam, f.filmnaam, f.genre, fa.rol
    FROM acteur a
    LEFT JOIN film_acteur fa ON a.acteur_id = fa.acteur_id
    LEFT JOIN film f ON fa.film_id = f.film_id
    WHERE a.acteur_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $acteur_id);
$stmt->execute();
$result = $stmt->get_result();

$acteurnaam = "";
$films = [];
while ($row = $result->fetch_assoc()) {
    $acteurnaam = $row['acteurnaam'];
    if ($row['filmnaam']) $films[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Mijn Info</title>
<link rel="stylesheet" href="robcss.css">
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

<h2>Welkom, <?= htmlspecialchars($acteurnaam ?: "Onbekend") ?></h2>

<?php if (!empty($films)): ?>
<table>
<tr><th>Film</th><th>Genre</th><th>Rol</th></tr>
<?php foreach ($films as $film): ?>
<tr>
    <td><?= htmlspecialchars($film['filmnaam']) ?></td>
    <td><?= htmlspecialchars($film['genre']) ?></td>
    <td><?= htmlspecialchars($film['rol']) ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php else: ?>
<p>Je speelt nog niet in een film.</p>
<?php endif; ?>

</body>
</html>
