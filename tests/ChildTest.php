<?php

namespace iggyvolz\ClassProperties\tests;

use iggyvolz\ClassProperties\examples\TestChildClass;
use PHPUnit\Framework\TestCase;

class ChildTest extends TestCase
{
    public function testBasicUsage()
    {
        $instance = new TestChildClass();
        $instance->prop = 4;
        $this->assertSame(4, $instance->prop);
    }
    public function testBasicUsageInherited()
    {
        $instance = new TestChildClass();
        $instance->otherProp = 4;
        $this->assertSame(4, $instance->otherProp);
    }
    public function testReadOnlyCanRead()
    {
        $instance = new TestChildClass();
        $this->assertSame(8, $instance->readOnlyProp);
    }
    public function testCannotWriteReadOnly()
    {
        $instance = new TestChildClass();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid property access readOnlyProp on " . TestChildClass::class);
        $instance->readOnlyProp = 4;
    }
    public function testCallsGetter()
    {
        $instance = new TestChildClass();
        $this->expectOutputString("Calling someGetter");
        $this->assertSame(2, $instance->dynamicReadProp);
    }
    public function testCannotWriteWithoutSetter()
    {
        $instance = new TestChildClass();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid property access dynamicReadProp on " . TestChildClass::class);
        $instance->dynamicReadProp = 3;
    }
    public function testCannotReadWithoutGetter()
    {
        $instance = new TestChildClass();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid property access dynamicWriteProp on " . TestChildClass::class);
        $instance->dynamicWriteProp;
    }
    public function testCallsSetter()
    {
        $instance = new TestChildClass();
        $this->expectOutputString("Calling someSetter(7)");
        $instance->dynamicWriteProp = 7;
    }
    public function testCallsGetterWithBoth()
    {
        $instance = new TestChildClass();
        $this->expectOutputString("Calling someOtherGetter");
        $this->assertSame(10, $instance->dynamicProp);
    }
    public function testCallsSetterWithBoth()
    {
        $instance = new TestChildClass();
        $this->expectOutputString("Calling someOtherSetter(73)");
        $instance->dynamicProp = 73;
    }

    public function testDebugInfo()
    {
        $instance = new TestChildClass();
        $this->expectOutputRegex("/.*/");
        $this->assertSame([
            "otherProp" => -1,
            "prop" => -1,
            "readOnlyProp" => 8,
            "dynamicReadProp" => 2,
            "dynamicProp" => 10,
        ], $instance->__debugInfo());
    }

    public function testUnset()
    {
        $instance = new TestChildClass();
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Invalid unset on " . TestChildClass::class);
        unset($instance->prop);
    }

    public function testIsset()
    {
        $instance = new TestChildClass();
        $this->assertTrue(isset($instance->prop));
    }

    public function testIssetDynamic()
    {
        $instance = new TestChildClass();
        $this->assertTrue(isset($instance->dynamicReadProp));
    }

    public function testIssetError()
    {
        $instance = new TestChildClass();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid property access dynamicWriteProp on " . TestChildClass::class);
        $this->assertNull(isset($instance->dynamicWriteProp));
    }
}
