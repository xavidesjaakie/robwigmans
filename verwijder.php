<?php
include 'database.php';
$id = $_GET['id'];


$stmt = $pdo->prepare("DELETE FROM bestemming WHERE idbestemming = ?");
$stmt->execute([$id]);

header('Location: index.php');
exit;
?>
