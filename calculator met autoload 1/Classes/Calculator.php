<?php
namespace Xxvan\Calculatorzonderautoload\Classes;

class Calculator
{
    public function __construct()
    {
        echo "Calculator class loaded.<br>";
    }

    public function add($a, $b)
    {
        return $a + $b;
    }
}
