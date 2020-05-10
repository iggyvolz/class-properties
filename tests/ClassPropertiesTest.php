<?php

namespace iggyvolz\ClassProperties\tests;

use iggyvolz\ClassProperties\examples\TestClass;
use PHPUnit\Framework\TestCase;

class ClassPropertiesTest extends TestCase
{
    public function testBasicUsage()
    {
        $instance = new TestClass();
        $instance->prop = 4;
        $this->assertSame(4, $instance->prop);
    }
    public function testReadOnlyCanRead()
    {
        $instance = new TestClass();
        $this->assertSame(8, $instance->readOnlyProp);
    }
    public function testCannotWriteReadOnly()
    {
        $instance = new TestClass();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid property access readOnlyProp on " . TestClass::class);
        $instance->readOnlyProp = 4;
    }
    public function testCallsGetter()
    {
        $instance = new TestClass();
        $this->expectOutputString("Calling someGetter");
        $this->assertSame(2, $instance->dynamicReadProp);
    }
    public function testCannotWriteWithoutSetter()
    {
        $instance = new TestClass();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid property access dynamicReadProp on " . TestClass::class);
        $instance->dynamicReadProp = 3;
    }
    public function testCannotReadWithoutGetter()
    {
        $instance = new TestClass();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid property access dynamicWriteProp on " . TestClass::class);
        $instance->dynamicWriteProp;
    }
    public function testCallsSetter()
    {
        $instance = new TestClass();
        $this->expectOutputString("Calling someSetter(7)");
        $instance->dynamicWriteProp = 7;
    }
    public function testCallsGetterWithBoth()
    {
        $instance = new TestClass();
        $this->expectOutputString("Calling someOtherGetter");
        $this->assertSame(10, $instance->dynamicProp);
    }
    public function testCallsSetterWithBoth()
    {
        $instance = new TestClass();
        $this->expectOutputString("Calling someOtherSetter(73)");
        $instance->dynamicProp = 73;
    }
}
