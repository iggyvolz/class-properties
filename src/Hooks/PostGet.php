<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\Hooks;

use iggyvolz\ClassProperties\ClassProperties;

/**
 * Hook that runs after a __get operation
 */
interface PostGet
{
    /**
     * @param mixed $value Value which was passed to this hook and will be returned to the caller or next hook
     */
    public function runPostGetHook(ClassProperties $target, string $property, &$value): void;
}
