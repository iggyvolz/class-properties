<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\examples;

use iggyvolz\ClassProperties\Attributes\Identifier;
use iggyvolz\ClassProperties\Attributes\Property;
use iggyvolz\ClassProperties\Identifiable;

/**
 * Invalid example for Identifiable - identifier is untyped
 */
class ExampleInvalidUntypedIdentifiable extends Identifiable
{
    // DO NOT DO THIS
    <<Property>>
    <<Identifier>>
    /**
     * @var mixed
     */
    protected $id = "";
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