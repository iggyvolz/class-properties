<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
/**
 * Documents a property for an object
 * @property-read bool $CanRead
 * @property-read bool $CanWrite
 */
class Property
{
    private bool $CanRead;
    private bool $CanWrite;
    public function __construct(bool $canRead = true, bool $canWrite = true)
    {
        // @phan-suppress-next-line PhanAccessReadOnlyMagicProperty
        $this->CanRead = $canRead;
        // @phan-suppress-next-line PhanAccessReadOnlyMagicProperty
        $this->CanWrite = $canWrite;
    }
    public function __get(string $name): ?bool
    {
        switch ($name) {
            case "CanRead":
                return $this->CanRead;
            case "CanWrite":
                return $this->CanWrite;
            default:
                // @codeCoverageIgnoreStart
                return null;
                // @codeCoverageIgnoreEnd
        }
    }
}
