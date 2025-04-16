<?php
include 'database.php';
$id = $_GET['id'];
$bestemming = $pdo->query("SELECT * FROM bestemming WHERE idbestemming = '$id'")->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plaats = $_POST['plaats'];
    $land = $_POST['land'];
    $werelddeel = $_POST['werelddeel'];
    
    $sql = "UPDATE bestemming SET plaats = ?, land = ?, werelddeel = ? WHERE idbestemming = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$plaats, $land, $werelddeel, $id]);

    header('Location: index.php');
    exit;
}
?>

<form method="POST">
    <label>Plaats:</label>
    <input type="text" name="plaats" value="<?= htmlspecialchars($bestemming['plaats']) ?>" required>
    
    <label>Land:</label>
    <input type="text" name="land" value="<?= htmlspecialchars($bestemming['land']) ?>" required>
    
    <label>Werelddeel:</label>
    <input type="text" name="werelddeel" value="<?= htmlspecialchars($bestemming['werelddeel']) ?>" required>
    
    <button type="submit">Wijzigen</button>
</form>
