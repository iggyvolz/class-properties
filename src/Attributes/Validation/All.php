<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\Attributes\Validation;

use Attribute;
use iggyvolz\ClassProperties\Hooks\PreSet;
use iggyvolz\ClassProperties\Identifiable;
use iggyvolz\ClassProperties\Attributes\ReadOnlyProperty;

<<Attribute(Attribute::TARGET_PROPERTY)>>
class All extends Validation
{
    /**
     * @var Validation[]
     */
    <<ReadOnlyProperty>>
    private array $validations;
    public function __construct(
        Validation ...$validations
    ) {
        $this->validations = $validations;
    }
    public function check($value):void
    {
        foreach($this->validations as $validation) {
            $validation->check($value);
        }
    }
}