<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\Attributes;

use iggyvolz\virtualattributes\VirtualAttribute;

//<<PhpAttribute>>
/**
 * Documents the identifier for the object
 */
class Identifier extends VirtualAttribute
{
    public function __construct()
    {
        parent::__construct();
    }
}
