<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\Hooks;

use iggyvolz\ClassProperties\ClassProperties;

/**
 * Hook that runs before a __set operation
 */
interface PreSet
{
    /**
     * @param mixed $value Value which was passed to this hook and will be returned to the caller or next hook
     */
    public function runPreSetHook(ClassProperties $target, string $property, &$value): void;
}
