<?php
// auteur: studentnaam
// functie: insert class Klant

// Autoloader classes via composer
require '../../vendor/autoload.php';
use Bas\classes\Klant;

if(isset($_POST["insert"]) && $_POST["insert"] == "Toevoegen"){

		// Code insert klant
} 

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

	<h1>CRUD Klant</h1>
	<h2>Toevoegen</h2>
	<form method="post">
	<label for="nv">Klantnaam:</label>
	<input type="text" id="nv" name="klantnaam" placeholder="Klantnaam" required/>
	<br>   
	<label for="an">Klantemail:</label>
	<input type="text" id="an" name="klantemail" placeholder="Klantemail" required/>
	<br><br>
	<input type='submit' name='insert' value='Toevoegen'>
	</form></br>

	<a href='read.php'>Terug</a>

</body>
</html>



