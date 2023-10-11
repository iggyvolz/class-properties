<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\examples;

use iggyvolz\ClassProperties\Attributes\Property;

class TestChildClass extends TestClass
{
    #[Property]
    protected int $otherProp = -1;
}
