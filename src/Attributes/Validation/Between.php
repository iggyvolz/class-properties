<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\Attributes\Validation;

use Attribute;
use iggyvolz\ClassProperties\Hooks\PreSet;
use iggyvolz\ClassProperties\Identifiable;
use iggyvolz\ClassProperties\Attributes\ReadOnlyProperty;

<<Attribute(Attribute::TARGET_PROPERTY)>>
class Between extends All
{
    <<ReadOnlyProperty>>
    private bool $inclusiveMin;
    <<ReadOnlyProperty>>
    private bool $inclusiveMax;
    public function __construct(
        <<ReadOnlyProperty>>
        private float|int $min,
        <<ReadOnlyProperty>>
        private float|int $max,
        bool $inclusive,
        ?bool $inclusiveMax = null
    ) {
        $this->inclusiveMin = $inclusive;
        $this->inclusiveMax = $inclusiveMax ?? $inclusive;
        $minCondition = ($this->inclusiveMin) ? new AtLeast($min) : new GreaterThan($min);
        $maxCondition = ($this->inclusiveMax) ? new AtMost($max) : new LessThan($max);
        parent::__construct($minCondition, $maxCondition);
    }
}