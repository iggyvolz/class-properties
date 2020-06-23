<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\Attributes\Validation;

use Attribute;
use iggyvolz\ClassProperties\Hooks\PreSet;
use iggyvolz\ClassProperties\Identifiable;

abstract class Validation extends PreSet
{
    public function runPreSetHook(ClassProperties $target, string $property, &$value): void
    {
        if(!is_null($value)) {
            $this->check($value);
        }
    }
    abstract public function check($value):void;
}