<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\examples;

use iggyvolz\ClassProperties\Attributes\Getter;
use iggyvolz\ClassProperties\Attributes\Property;
use iggyvolz\ClassProperties\Attributes\ReadOnlyProperty;
use iggyvolz\ClassProperties\Attributes\Setter;
use iggyvolz\ClassProperties\ClassProperties;

class TestChildClass extends TestClass
{
    // <<Property>>
    protected int $otherProp = -1;
}
(new Property())->addToProperty(TestChildClass::class, "otherProp");