<?php
include 'database.php';


$merken = $pdo->query("SELECT * FROM merk")->fetchAll(PDO::FETCH_ASSOC);
$types = $pdo->query("SELECT * FROM type")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $merk_id = $_POST['merk_id'];
    $type_id = $_POST['type_id'];
    $naam = $_POST['naam'];
    $prijs = $_POST['prijs'];

    $sql = "INSERT INTO product (soort_id, type_id, naam, prijs) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$merk_id, $type_id, $naam, $prijs]);

    header('Location: index.php');
    exit;
}
?>

<form method="POST">
    <label>Merk:</label>
    <select name="merk_id">
        <?php foreach ($merken as $merk): ?>
            <option value="<?= $merk['id'] ?>"><?= htmlspecialchars($merk['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <label>Type:</label>
    <select name="type_id">
        <?php foreach ($types as $type): ?>
            <option value="<?= $type['id'] ?>"><?= htmlspecialchars($type['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <label>Naam:</label>
    <input type="text" name="naam" required>
    <label>Prijs:</label>
    <input type="number" name="prijs" required>
    <button type="submit">Toevoegen</button>
</form>
