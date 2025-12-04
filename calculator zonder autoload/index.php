<?php
require_once 'Classes/Calculator.php'; 

$calc = new Calculator();
$result = $calc->add(5, 7);

echo "Resultaat: " . $result;

?>