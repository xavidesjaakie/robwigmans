<?php
include 'database.php';

function getBestemmingen($pdo) {
    $sql = "SELECT idbestemming, plaats, land, werelddeel FROM bestemming";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function voegBestemmingToe($pdo, $id, $plaats, $land, $werelddeel) {
    $sql = "INSERT INTO bestemming (idbestemming, plaats, land, werelddeel) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id, $plaats, $land, $werelddeel]);
}


function verwijderBestemming($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM bestemming WHERE idbestemming = ?");
    $stmt->execute([$id]);
}


function getBestemming($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM bestemming WHERE idbestemming = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function updateBestemming($pdo, $id, $plaats, $land, $werelddeel) {
    $sql = "UPDATE bestemming SET plaats = ?, land = ?, werelddeel = ? WHERE idbestemming = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$plaats, $land, $werelddeel, $id]);
}
?>
