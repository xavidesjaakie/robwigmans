<?php
include 'functions.php';
$id = $_GET['id'];

verwijderBestemming($pdo, $id);

header('Location: index.php');
exit;
?>
