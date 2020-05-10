<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\Attributes;

//<<PhpAttribute>>
use iggyvolz\virtualattributes\VirtualAttribute;

/**
 * Documents a getter for a virtual property
 * @property-read string $PropertyName
 */
class Setter extends VirtualAttribute
{
    private string $PropertyName;
    public function __construct(string $propertyName)
    {
        // @phan-suppress-next-line PhanAccessReadOnlyMagicProperty
        $this->PropertyName = $propertyName;
        parent::__construct($propertyName);
    }
    public function __get(string $name): ?string
    {
        switch ($name) {
            case "PropertyName":
                return $this->PropertyName;
            default:
                return null;
        }
    }
}
