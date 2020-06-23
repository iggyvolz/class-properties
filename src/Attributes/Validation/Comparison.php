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
class Comparison extends Validation
{
    public const LESS = 1 << 0;
    public const EQUAL = 1 << 1;
    public const GREATER = 1 << 2;
    public function __construct(
        <<ReadOnlyProperty>>
            float|int $checkValue,
        <<ReadOnlyProperty>>
            private int $checks
    ) {
        $this->checks &= self::LESS | self::EQUAL | self::GREATER;
    }
    public function check($value): void
    {
        if(!is_int($value) && !is_float($value)) {
            throw new ValidationException("Invalid type ".get_debug_type($value).", needed int|float");
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
        $comparison = self::getCheckType($this->checks);
        if(!($this->checks & $result)) {
            throw new ValidationException($this->checkValue. " is not $comparison $value");
        }
    }
    public static function getCheckType(int $type)
    {
        // return match($this->checks) {
        //     self::GREATER => "greater than",
        //     self::EQUAL => "equal to",
        //     self::GREATER | self::EQUAL => "greater than or equal to",
        //     self::LESS => "less than",
        //     self::LESS | self::GREATER => "unequal to",
        //     self::LESS | self::EQUAL => "less than or equal to",
        //     default => "(nonsensical comparision ".$this->checks.")"
        // }

        switch($this->checks) {
            case self::GREATER: return "greater than";
            case self::EQUAL: return "equal to";
            case self::GREATER | self::EQUAL: return "greater than or equal to";
            case self::LESS: return "less than";
            case self::LESS | self::GREATER: return "unequal to";
            case self::LESS | self::EQUAL: return "less than or equal to";
            default: return "(nonsensical comparision ".$this->checks.")";
        }
    }
}