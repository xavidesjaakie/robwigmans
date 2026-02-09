<?php
// auteur: studentnaam
// functie: definitie class Klant
namespace Bas\classes;

use Bas\classes\Database;
use Bas\classes\TableHelper;

class Klant extends Database{
	public $klantId;
	public $klantemail = null;
	public $klantnaam;
	public $klantwoonplaats;
	private $table_name = "Klant";	

	// Methods
	
	/**
	 * Summary of crudKlant
	 * @return void
	 */
	public function crudKlant() : void {
		// Haal alle klant op uit de database mbv de method getKlant()
		$lijst = $this->getKlanten();

		// Print een HTML tabel van de lijst	
		$this->showTable($lijst);
	}

	/**
	 * Summary of getKlant
	 * @return mixed
	 */
	public function getKlanten() : array {
		// testdata
		$lijst = [
            ['klantId' => 1, 'klantEmail' => 'test1@example.com', 'klantNaam' => 'Test 1', 'klantWoonplaats' => 'City 1'],
            ['klantId' => 2, 'klantEmail' => 'test2@example.com', 'klantNaam' => 'Test 2', 'klantWoonplaats' => 'City 2']
            // Add more expected data as needed
        ];

		// Doe een query: dit is een prepare en execute in 1 zonder placeholders
		// $lijst = $conn->query("select invullen")->fetchAll();
		
		return $lijst;
	}

 /**
  * Summary of getKlant
  * @param int $klantId
  * @return mixed
  */
	public function getKlant(int $klantId) : array {

		// Doe een fetch op $klantId
		
		// testdata
		$lijst = 
            ['klantId' => 1, 'klantEmail' => 'test1@example.com', 'klantNaam' => 'Test 1', 'klantWoonplaats' => 'City 1']
        ;

		return $lijst;
	}
	
	public function dropDownKlant($row_selected = -1){
	
		// Haal alle klanten op uit de database mbv de method getKlanten()
		$lijst = $this->getKlanten();
		
		echo "<label for='Klant'>Choose a klant:</label>";
		echo "<select name='klantId'>";
		foreach ($lijst as $row){
			if($row_selected == $row["klantId"]){
				echo "<option value='$row[klantId]' selected='selected'> $row[klantNaam] $row[klantEmail]</option>\n";
			} else {
				echo "<option value='$row[klantId]'> $row[klantNaam] $row[klantEmail]</option>\n";
			}
		}
		echo "</select>";
	}

 /**
  * Summary of showTable
  * @param mixed $lijst
  * @return void
  */
	public function showTable($lijst) : void {

		$txt = "<table>";

		// Voeg de kolomnamen boven de tabel
		$txt .= TableHelper::getTableHeader($lijst[0]);

		foreach($lijst as $row){
			$txt .= "<tr>";
			$txt .=  "<td>" . $row["klantId"] . "</td>";
			$txt .=  "<td>" . $row["klantNaam"] . "</td>";
			$txt .=  "<td>" . $row["klantEmail"] . "</td>";
			$txt .=  "<td>" . $row["klantWoonplaats"] . "</td>";
			
			//Update
			// Wijzig knopje
        	$txt .=  "<td>";
			$txt .= " 
            <form method='post' action='update.php?klantId={$row['klantId']}' >       
                <button name='update'>Wzg</button>	 
            </form> </td>";

			//Delete
			$txt .=  "<td>";
			$txt .= " 
            <form method='post' action='delete.php?klantId={$row['klantId']}' >       
                <button name='verwijderen'>Verwijderen</button>	 
            </form> </td>";	
			$txt .= "</tr>";
		}
		$txt .= "</table>";
		echo $txt;
	}

	// Delete klant
 /**
  * Summary of deleteKlant
  * @param int $klantId
  * @return bool
  */
	public function deleteKlant(int $klantId) : bool {

		return true;
	
	}

	public function updateKlant($row) : bool{

		return true;
	}
	
	
	/**
	 * Summary of BepMaxKlantId
	 * @return int
	 */
	private function BepMaxKlantId() : int {
		
	// Bepaal uniek nummer
	$sql="SELECT MAX(klantId)+1 FROM $this->table_name";
	return  (int) self::$conn->query($sql)->fetchColumn();
}
	
	
	/**
	 * Summary of insertKlant
	 * @param mixed $row
	 * @return mixed
	 */
	public function insertKlant($row){
		
		// Bepaal een unieke klantId
		$klantId = $this->BepMaxKlantId();

		// query
		
		
		// Prepare
		
		
		// Execute 'klantId'=>$klantId,
				
	}
}
?>