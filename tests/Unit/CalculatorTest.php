<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Calculator;

class CalculatorTest extends TestCase
{
    private Calculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new Calculator();
    }

    public function testAddition(): void
    {
        $result = $this->calculator->add(2, 3);
        $this->assertEquals(5, $result);
    }

    public function testSubtraction(): void
    {
        $result = $this->calculator->subtract(5, 3);
        $this->assertEquals(2, $result);
    }

    public function testMultiplication(): void
    {
        $result = $this->calculator->multiply(3, 4);
        $this->assertEquals(12, $result);
    }

    public function testDivision(): void
    {
        $result = $this->calculator->divide(10, 2);
        $this->assertEquals(5.0, $result);
    }

    public function testDivisionByZero(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Division by zero');
        $this->calculator->divide(10, 0);
    }
}