<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\examples;

use iggyvolz\ClassProperties\Attributes\Getter;
use iggyvolz\ClassProperties\Attributes\Identifier;
use iggyvolz\ClassProperties\Attributes\Property;
use iggyvolz\ClassProperties\Identifiable;

/**
 * Invalid example for Identifiable - two identifiers
 */
class ExampleInvalidIdentifiableDuplicateIdentifier extends Identifiable
{
    // <<Property>>
    // <<Identifier>>
    protected int $id1;
    // <<Property>>
    // <<Identifier>>
    protected int $id2;
    public static function getFromIdentifier($identifier): ?self
    {
        return null;
    }
}

(new Property)->addToProperty(ExampleInvalidIdentifiableDuplicateIdentifier::class, "id1");
(new Identifier)->addToProperty(ExampleInvalidIdentifiableDuplicateIdentifier::class, "id1");
(new Property)->addToProperty(ExampleInvalidIdentifiableDuplicateIdentifier::class, "id2");
(new Identifier)->addToProperty(ExampleInvalidIdentifiableDuplicateIdentifier::class, "id2");