<?php

namespace iggyvolz\ClassProperties\tests;

use iggyvolz\ClassProperties\examples\ExampleWithHooks;
use PHPUnit\Framework\TestCase;

class HooksTest extends TestCase
{
    public function testPreGetHook()
    {
        $instance = new ExampleWithHooks();
        $this->expectOutputString("PREGET " . spl_object_id($instance) . ":preGetHook");
        $this->assertSame(1, $instance->preGetHook);
    }
    public function testPostGetHook()
    {
        $instance = new ExampleWithHooks();
        $this->expectOutputString("POSTGET " . spl_object_id($instance) . ":postGetHook (was 2)");
        // note the value is changed before being returned
        // do not use this power for evil
        $this->assertSame(3, $instance->postGetHook);
    }
    public function testPreSetHook()
    {
        $instance = new ExampleWithHooks();
        $this->expectOutputString("PRESET " . spl_object_id($instance) . ":preSetHook (was 4)");
        $instance->preSetHook = 4;
        // note the value was changed before being set
        // do not use this power for evil
        $this->assertSame(3, $instance->preSetHook);
    }
    public function testPostSetHook()
    {
        $instance = new ExampleWithHooks();
        $this->expectOutputString("POSTSET " . spl_object_id($instance) . ":postSetHook (5)");
        $instance->postSetHook = 5;
        // note the value is changed before being returned
        // do not use this power for evil
        $this->assertSame(5, $instance->postSetHook);
    }
}
