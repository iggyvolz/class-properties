<?php

namespace iggyvolz\ClassProperties\tests;

use PHPUnit\Framework\TestCase;
use iggyvolz\ClassProperties\Attributes\Validation\AtMost;
use iggyvolz\ClassProperties\Attributes\Validation\AtLeast;
use iggyvolz\ClassProperties\Attributes\Validation\Between;
use iggyvolz\ClassProperties\Attributes\Validation\LessThan;
use iggyvolz\ClassProperties\Attributes\Validation\GreaterThan;
use iggyvolz\ClassProperties\Attributes\Validation\ValidationException;

class ConditionTest extends TestCase
{
    public function testLessThan():void
    {
        $this->assertTrue(true);
        $condition = new LessThan(8);
        $condition->check(6);
    }
    public function testLessThanErrors():void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("8 is not less than 6");
        $condition = new LessThan(6);
        $condition->check(8);
    }
    public function testGreaterThan():void
    {
        $this->assertTrue(true);
        $condition = new GreaterThan(6);
        $condition->check(8);
    }
    public function testGreaterThanErrors():void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("6 is not greater than 8");
        $condition = new GreaterThan(8);
        $condition->check(6);
    }
    public function testAtMost():void
    {
        $this->assertTrue(true);
        $condition = new AtMost(6);
        $condition->check(6);
    }
    public function testAtMostErrors():void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("9 is not less than or equal to 8");
        $condition = new AtMost(8);
        $condition->check(9);
    }
    public function testAtLeast():void
    {
        $this->assertTrue(true);
        $condition = new AtLeast(6);
        $condition->check(6);
    }
    public function testLeastErrors():void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("7 is not greater than or equal to 8");
        $condition = new AtLeast(8);
        $condition->check(7);
    }
    public function testBetweenInclusive():void
    {
        $this->assertTrue(true);
        $condition = new Between(6, 8, true);
        $condition->check(6);
        $condition->check(7);
        $condition->check(8);
    }
}
