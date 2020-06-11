<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\examples;

use iggyvolz\ClassProperties\ClassProperties;
use iggyvolz\ClassProperties\Hooks\PostSet;
use Attribute;

<<Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD)>>
class ExamplePostSetHook implements PostSet
{

    public function runPostSetHook(ClassProperties $target, string $property, $value): void
    {
        if (!is_int($value)) {
            throw new \LogicException("Example was meant to run with integers");
        }
        echo "POSTSET " . spl_object_id($target) . ":$property ($value)";
    }
}
