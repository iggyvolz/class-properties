<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\examples;

use iggyvolz\ClassProperties\Attributes\Getter;
use iggyvolz\ClassProperties\Attributes\Identifier;
use iggyvolz\ClassProperties\Attributes\Property;
use iggyvolz\ClassProperties\Identifiable;

/**
 * Example where $id can be one of 1, 3, or 5
 * @property int $id
 * @property-read int $idPlusOne
 */
class ExampleIdentifiable extends Identifiable
{
    @@Property
    @@Identifier
    protected int $id = 0;
    @@Getter("idPlusOne")
    protected function getIdPlusOne(): int
    {
        return $this->__get("id") + 1;
    }
    /**
     * @param int|string|Identifiable $identifier
     * @return static|null
     * @phan-suppress PhanParamSignatureRealMismatchReturnType https://github.com/phan/phan/issues/3795
     */
    public static function getFromIdentifier($identifier): ?self
    {
        $instance = new static();
        if (!in_array($identifier, [1,3,5], true)) {
            return null;
        }
        $instance->__set("id", $identifier);
        return $instance;
    }
}