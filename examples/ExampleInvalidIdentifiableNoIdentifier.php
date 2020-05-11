<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\examples;

use iggyvolz\ClassProperties\Attributes\Getter;
use iggyvolz\ClassProperties\Attributes\Identifier;
use iggyvolz\ClassProperties\Attributes\Property;
use iggyvolz\ClassProperties\Identifiable;

/**
 * Invalid example for Identifiable - no identifier
 */
class ExampleInvalidIdentifiableNoIdentifier extends Identifiable
{
    public static function getFromIdentifier($identifier): ?self
    {
        return null;
    }
}