<?php
include 'database.php';

$sql = "SELECT product.id, merk.name AS merk, type.name AS type, product.naam, product.prijs 
        FROM product 
        JOIN merk ON product.soort_id = merk.id 
        JOIN type ON product.type_id = type.id";
$stmt = $pdo->query($sql);
$fietsen = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Fietsen Beheer</title>
</head>
<body>
    <a href="toevoegen.php">Fiets toevoegen</a>
    <table border="1">
        <tr>
            <th>Merk</th>
            <th>Type</th>
            <th>Naam</th>
            <th>Prijs</th>
            <th>Wijzig</th>
            <th>Verwijderen</th>
        </tr>
        <?php foreach ($fietsen as $fiets): ?>
            <tr>
                <td><?= htmlspecialchars($fiets['merk']) ?></td>
                <td><?= htmlspecialchars($fiets['type']) ?></td>
                <td><?= htmlspecialchars($fiets['naam']) ?></td>
                <td><?= htmlspecialchars($fiets['prijs']) ?></td>
                <td><a href="wijzig.php?id=<?= $fiets['id'] ?>">Wijzig</a></td>
                <td><a href="verwijder.php?id=<?= $fiets['id'] ?>" onclick="return confirm('Weet je zeker dat je deze fiets wilt verwijderen?')">Verwijder</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
