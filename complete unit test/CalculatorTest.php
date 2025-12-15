<?php

use PHPUnit\Framework\TestCase;
use Xxvan\calculatormetautoload\Classes\Calculator;

class CalculatorTest extends TestCase
{
    public function testAddOne()
    {
        $calculator = new Calculator();
        $this->assertEquals(5, $calculator->add(2, 3));
    }

    public function testAddTwo()
    {
        $calculator = new Calculator();
        $this->assertEquals(10, $calculator->add(7, 3));
    }

    public function testAddThree()
    {
        $calculator = new Calculator();
        $this->assertEquals(0, $calculator->add(-5, 5));
    }

    public function testSubtractOne()
    {
        $calculator = new Calculator();
        $this->assertEquals(6, $calculator->subtract(10, 4));
    }

    public function testSubtractTwo()
    {
        $calculator = new Calculator();
        $this->assertEquals(-2, $calculator->subtract(3, 5));
    }

    public function testSubtractThree()
    {
        $calculator = new Calculator();
        $this->assertEquals(0, $calculator->subtract(8, 8));
    }

    public function testMultiplyOne()
    {
        $calculator = new Calculator();
        $this->assertEquals(12, $calculator->multiply(3, 4));
    }

    public function testMultiplyTwo()
    {
        $calculator = new Calculator();
        $this->assertEquals(0, $calculator->multiply(5, 0));
    }

    public function testMultiplyThree()
    {
        $calculator = new Calculator();
        $this->assertEquals(-15, $calculator->multiply(-3, 5));
    }
    
    public function testDivideOne()
    {
        $calculator = new Calculator();
        $this->assertEquals(5, $calculator->divide(10, 2));
    }

    public function testDivideTwo()
    {
        $calculator = new Calculator();
        $this->assertEquals(2.5, $calculator->divide(5, 2));
    }

    public function testDivideThree()
    {
        $calculator = new Calculator();

        $this->expectException(Exception::class);
        $calculator->divide(10, 0);
    }
}
