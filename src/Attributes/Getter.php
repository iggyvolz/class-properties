<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\Attributes;

use Attribute;

<<Attribute(Attribute::TARGET_METHOD)>>
/**
 * Documents a getter for a virtual property
 * @property-read string $PropertyName
 */
class Getter
{
    private string $PropertyName;
    public function __construct(string $propertyName)
    {
        // @phan-suppress-next-line PhanAccessReadOnlyMagicProperty
        $this->PropertyName = $propertyName;
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
