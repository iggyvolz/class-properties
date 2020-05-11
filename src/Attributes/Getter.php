<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\Attributes;

use iggyvolz\virtualattributes\VirtualAttribute;

//<<PhpAttribute>>
/**
 * Documents a getter for a virtual property
 * @property-read string $PropertyName
 */
class Getter extends VirtualAttribute
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
                // @codeCoverageIgnoreStart
                return null;
                // @codeCoverageIgnoreEnd
        }
    }
}
