<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\examples;

use iggyvolz\ClassProperties\ClassProperties;
use iggyvolz\ClassProperties\Hooks\PostSet;
use iggyvolz\virtualattributes\VirtualAttribute;

class ExamplePostSetHook extends VirtualAttribute implements PostSet
{

    public function runPostSetHook(ClassProperties $target, string $property, $value): void
    {
        if (!is_int($value)) {
            throw new \LogicException("Example was meant to run with integers");
        }
        echo "POSTSET " . spl_object_id($target) . ":$property ($value)";
    }
}
