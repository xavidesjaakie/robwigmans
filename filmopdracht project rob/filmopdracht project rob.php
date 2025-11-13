<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "film project";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("Verbinding mislukt: " . $conn->connect_error);

$sql = "
    SELECT 
        f.film_id,
        f.filmnaam,
        f.genre,
        a.acteurnaam,
        fa.rol
    FROM film f
    LEFT JOIN film_acteur fa ON f.film_id = fa.film_id
    LEFT JOIN acteur a ON fa.acteur_id = a.acteur_id
    ORDER BY f.filmnaam, a.acteurnaam
";
$result = $conn->query($sql);

$films = [];
while ($row = $result->fetch_assoc()) {
    $films[$row['film_id']]['filmnaam'] = $row['filmnaam'];
    $films[$row['film_id']]['genre'] = $row['genre'];
    $films[$row['film_id']]['acteurs'][] = [
        'naam' => $row['acteurnaam'],
        'rol' => $row['rol']
    ];
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Film Overzicht</title>
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

<div class="films-container">
<?php foreach ($films as $film): ?>
    <div class="film-card">
        <div class="film-image" style="background-image:url('images/placeholder.jpg');"></div>
        <div class="film-title"><?= htmlspecialchars($film['filmnaam']) ?></div>
        <div class="film-overlay">
            <h2><?= htmlspecialchars($film['filmnaam']) ?></h2>
            <p><strong>Genre:</strong> <?= htmlspecialchars($film['genre']) ?></p>
            <div>
                <strong>Acteurs:</strong><br>
                <?php 
                if (!empty($film['acteurs'])) {
                    foreach ($film['acteurs'] as $a) {
                        if ($a['naam']) echo htmlspecialchars($a['naam'])." als ".htmlspecialchars($a['rol'])."<br>";
                    }
                } else {
                    echo "<em>Geen acteurs toegevoegd</em>";
                }
                ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

</body>
</html>
