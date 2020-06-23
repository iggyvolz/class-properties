<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\Attributes\Validation;

use Attribute;
use iggyvolz\ClassProperties\Hooks\PreSet;
use iggyvolz\ClassProperties\Identifiable;
use iggyvolz\ClassProperties\Attributes\ReadOnlyProperty;

<<Attribute(Attribute::TARGET_PROPERTY)>>
class AtMost extends Comparison
{

    public function __construct(
        float|int $checkValue,
    ) {
        parent::__construct($checkValue, Comparison::LESS | Comparison::EQUAL);
    }
}