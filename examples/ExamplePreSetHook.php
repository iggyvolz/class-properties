<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\examples;

use iggyvolz\ClassProperties\ClassProperties;
use iggyvolz\ClassProperties\Hooks\PreSet;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD)]
class ExamplePreSetHook implements PreSet
{
    public function runPreSetHook(ClassProperties $target, string $property, &$value): void
    {
        if (!is_int($value)) {
            throw new \LogicException("Example was meant to run with integers");
        }
        $oldvalue = $value;
        $value--;
        echo "PRESET " . spl_object_id($target) . ":$property (was $oldvalue)";
    }
}
