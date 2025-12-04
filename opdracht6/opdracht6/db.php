<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "opdracht6"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Controleren of de verbinding werkt
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}
?>
