<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\Attributes\Validation;

use Attribute;
use TypeError;
use LogicException;
use iggyvolz\ClassProperties\Hooks\PreSet;
use iggyvolz\ClassProperties\Identifiable;
use iggyvolz\ClassProperties\Attributes\ReadOnlyProperty;

<<Attribute(Attribute::TARGET_PROPERTY)>>
class Matches extends Validation
{
    public function __construct(
        <<ReadOnlyProperty>>
            private string $regex
    ) {
    }
    public function check($value): void
    {
        if(!is_string($value)) {
            throw new ValidationException("Invalid type ".get_debug_type($value).", needed string");
        }
        if(preg_match($this->regex, $value) !== 1) {
            throw new ValidationException("$value does not match $regex");
        }
    }
}