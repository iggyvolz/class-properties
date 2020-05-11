<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\Hooks;

use iggyvolz\ClassProperties\ClassProperties;

/**
 * Hook that runs after a __set operation
 */
interface PostSet
{
    /**
     * @param mixed $value Value which the property was set to
     */
    public function runPostSetHook(ClassProperties $target, string $property, $value): void;
}
