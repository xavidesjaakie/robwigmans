<?php
// auteur: studentnaam
// functie: Helper class voor tabel functies 
namespace Bas\classes;

class TableHelper {
    
    /**
     * Summary of getTableHeader
     * @param array $row
     * @return string
     */
    public static function getTableHeader(array $row) : string {
        // haal de kolommen uit de eerste [0] van het array $result mbv array_keys
        $headers = array_keys($row);
        $headerTxt = "<tr>";
        foreach($headers as $header){
            $headerTxt .= "<th>" . $header . "</th>";   
        }
        $headerTxt .= "</tr>";
        return $headerTxt;
    }
}
?>
