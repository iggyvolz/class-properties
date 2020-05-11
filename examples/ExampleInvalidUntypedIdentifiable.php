<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\examples;

use iggyvolz\ClassProperties\Attributes\Getter;
use iggyvolz\ClassProperties\Attributes\Identifier;
use iggyvolz\ClassProperties\Attributes\Property;
use iggyvolz\ClassProperties\Identifiable;

/**
 * Invalid example for Identifiable - identifier is untyped
 */
class ExampleInvalidUntypedIdentifiable extends Identifiable
{
    // DO NOT DO THIS
    // <<Property>>
    // <<Identifier>>
    protected $id;
    public static function getFromIdentifier($identifier): ?self
    {
        return null;
    }
}

(new Property)->addToProperty(ExampleInvalidUntypedIdentifiable::class, "id");
(new Identifier)->addToProperty(ExampleInvalidUntypedIdentifiable::class, "id");