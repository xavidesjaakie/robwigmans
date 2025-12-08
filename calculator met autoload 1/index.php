<?php

require __DIR__ . '/vendor/autoload.php';

use Xxvan\Calculatorzonderautoload\Classes\Calculator;

$calc = new Calculator();
echo $calc->add(5, 7);
