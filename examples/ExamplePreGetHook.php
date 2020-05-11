<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\examples;

use iggyvolz\ClassProperties\ClassProperties;
use iggyvolz\ClassProperties\Hooks\PreGet;
use iggyvolz\virtualattributes\VirtualAttribute;

class ExamplePreGetHook extends VirtualAttribute implements PreGet
{

    public function runPreGetHook(ClassProperties $target, string $property): void
    {
        echo "PREGET " . spl_object_id($target) . ":$property";
    }
}
