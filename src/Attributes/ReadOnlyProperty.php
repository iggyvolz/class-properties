<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ReadOnlyProperty extends Property
{
    public function __construct()
    {
        parent::__construct(true, false);
    }
}
