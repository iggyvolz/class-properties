<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\Attributes\Validation;

use Attribute;
use iggyvolz\ClassProperties\Hooks\PreSet;
use iggyvolz\ClassProperties\Identifiable;
use iggyvolz\ClassProperties\Attributes\ReadOnlyProperty;

<<Attribute(Attribute::TARGET_PROPERTY)>>
class Any extends Validation
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
        $lastException = new ValidationException("Empty Any will always fail");
        foreach($this->validations as $validation) {
            try {
                $validation->check($value);
                return;
            } catch(ValidationException $e) {
                $lastException = $e;
            }
        }
        throw $lastException;
    }
}