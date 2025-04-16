

<?php
include 'database.php';
$id = $_GET['id'];
$pdo->query("DELETE FROM product WHERE id = $id");
header('Location: index.php');
exit;
?>
