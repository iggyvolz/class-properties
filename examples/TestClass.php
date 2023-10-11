<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\examples;

use iggyvolz\ClassProperties\Attributes\Getter;
use iggyvolz\ClassProperties\Attributes\Property;
use iggyvolz\ClassProperties\Attributes\ReadOnlyProperty;
use iggyvolz\ClassProperties\Attributes\Setter;
use iggyvolz\ClassProperties\ClassProperties;

/**
 * @property int $prop
 * @property int $readOnlyProp
 * @property int $dynamicReadProp
 * @property int $dynamicWriteProp
 * @property int $dynamicProp
 */
class TestClass extends ClassProperties
{
    #[Property]
    protected int $prop = -1;
    #[ReadOnlyProperty]
    protected int $readOnlyProp = 8;

    #[Getter("dynamicReadProp")]
    protected function someGetter(): int
    {
        echo "Calling someGetter";
        return 2;
    }

    #[Setter("dynamicWriteProp")]
    protected function someSetter(int $val): void
    {
        echo "Calling someSetter($val)";
    }

    #[Getter("dynamicProp")]
    protected function someOtherGetter(): int
    {
        echo "Calling someOtherGetter";
        return 10;
    }

    #[Setter("dynamicProp")]
    protected function someOtherSetter(int $val): void
    {
        echo "Calling someOtherSetter($val)";
    }
}
