<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\examples;

use iggyvolz\ClassProperties\Attributes\Identifier;
use iggyvolz\ClassProperties\Attributes\Property;
use iggyvolz\ClassProperties\Identifiable;

/**
 * Invalid example for Identifiable - two identifiers
 */
class ExampleInvalidIdentifiableDuplicateIdentifier extends Identifiable
{
    @@Property
    @@Identifier
    protected int $id1 = 0;
    @@Property
    @@Identifier
    protected int $id2 = 0;
    /**
     * @param int|string|Identifiable $identifier
     * @return static|null
     * @phan-suppress PhanParamSignatureRealMismatchReturnType https://github.com/phan/phan/issues/3795
     */
    public static function getFromIdentifier($identifier): ?self
    {
        return null;
    }
}
