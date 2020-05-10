<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\Attributes;

//<<PhpAttribute>>
class ReadOnlyProperty extends Property
{
    public function __construct()
    {
        parent::__construct(true, false);
    }
}
