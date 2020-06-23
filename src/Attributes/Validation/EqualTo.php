<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\Attributes\Validation;

use Attribute;
use iggyvolz\ClassProperties\Hooks\PreSet;
use iggyvolz\ClassProperties\Identifiable;
use iggyvolz\ClassProperties\Attributes\ReadOnlyProperty;

<<Attribute(Attribute::TARGET_PROPERTY)>>
class EqualTo extends Validation
{
    <<ReadOnlyProperty>>
    private float|int|string|bool $checkValue;
    public function __construct(
        <<ReadOnlyProperty>>
        float|int|string|bool|Identifiable $checkValue
    ) {
        if($checkValue instanceof Identifiable) {
            $this->checkValue = $checkValue->getIdentifier();
        } else {
            $this->checkValue = $checkValue;
        }
    }
    public function check($value): void
    {
        $passedValue = $value;
        if($passedValue instanceof Identifiable) {
            $passedValue = $passedValue->getIdentifier();
        }
        $checkValue = $this->checkValue;
        $checkType = get_debug_type($checkValue);
        $passedType = get_debug_type($passedValue);
        if($checkType !== $passedType) {
            throw new ValidationException("Invalid type $passedType, needed $checkType");
        }
        if($this->checkValue !== $passedValue)
        {
            throw new ValidationException("$checkValue is not equal to $passedValue");
        }
    }
}