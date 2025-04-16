<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idbestemming = $_POST['idbestemming'];
    $plaats = $_POST['plaats'];
    $land = $_POST['land'];
    $werelddeel = $_POST['werelddeel'];

    $sql = "INSERT INTO bestemming (idbestemming, plaats, land, werelddeel) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idbestemming, $plaats, $land, $werelddeel]);

    header('Location: index.php');
    exit;
}
?>

<form method="POST">
    <label>ID Bestemming:</label>
    <input type="text" name="idbestemming" required>

    <label>Plaats:</label>
    <input type="text" name="plaats" required>

    <label>Land:</label>
    <input type="text" name="land" required>

    <label>Werelddeel:</label>
    <input type="text" name="werelddeel" required>

    <button type="submit">Toevoegen</button>
</form>
