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
class LengthComparison extends Validation
{
    public function __construct(
        <<ReadOnlyProperty>>
            private int $checkValue,
        <<ReadOnlyProperty>>
            private int $checks
    ) {
        $this->checks &= Comparison::LESS | Comparison::EQUAL | Comparison::GREATER;
    }
    public function check($value): void
    {
        if(!is_string($value)) {
            throw new ValidationException("Invalid type ".get_debug_type($value).", needed string");
        }
        // $result = match($value <=> $this->checkValue) {
        //     -1 => self::LESS,
        //     0 => self::EQUAL,
        //     1 => self::GREATER
        // };
        switch($value <=> $this->checkValue) {
            case -1: $result = self::LESS; break;
            case 0: $result = self::EQUAL; break;
            case 1: $result = self::GREATER; break;
            default: throw new LogicException();
        }
        if(!($this->checks & $result)) {
            $comparison = Comparison::getCheckType($this->checks);
            throw new ValidationException("Length of '$value' is not $comparison $value");
        }
    }
}