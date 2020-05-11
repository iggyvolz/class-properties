<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\Hooks;

use iggyvolz\ClassProperties\ClassProperties;

/**
 * Hook that runs before a __get operation
 */
interface PreGet
{
    public function runPreGetHook(ClassProperties $target, string $property): void;
}
