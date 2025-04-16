

<?php
include 'database.php';
$id = $_GET['id'];
$fiets = $pdo->query("SELECT * FROM product WHERE id = $id")->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = $_POST['naam'];
    $prijs = $_POST['prijs'];
    
    $sql = "UPDATE product SET naam = ?, prijs = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$naam, $prijs, $id]);

    header('Location: index.php');
    exit;
}
?>

<form method="POST">
    <label>Naam:</label>
    <input type="text" name="naam" value="<?= htmlspecialchars($fiets['naam']) ?>" required>
    <label>Prijs:</label>
    <input type="number" name="prijs" value="<?= htmlspecialchars($fiets['prijs']) ?>" required>
    <button type="submit">Wijzigen</button>
</form>
