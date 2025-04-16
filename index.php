<?php
include 'functions.php';

$bestemmingen = getBestemmingen($pdo);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Reizen Beheer</title>
</head>
<body>
    <a href="toevoegen.php">Bestemming toevoegen</a>
    <table border="1">
        <tr>
            <th>Plaats</th>
            <th>Land</th>
            <th>Werelddeel</th>
            <th>Wijzig</th>
            <th>Verwijderen</th>
        </tr>
        <?php foreach ($bestemmingen as $bestemming): ?>
            <tr>
                <td><?= htmlspecialchars($bestemming['plaats']) ?></td>
                <td><?= htmlspecialchars($bestemming['land']) ?></td>
                <td><?= htmlspecialchars($bestemming['werelddeel']) ?></td>
                <td><a href="wijzig.php?id=<?= $bestemming['idbestemming'] ?>">Wijzig</a></td>
                <td><a href="verwijder.php?id=<?= $bestemming['idbestemming'] ?>" onclick="return confirm('Weet je zeker dat je deze bestemming wilt verwijderen?')">Verwijder</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
