<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\examples;

use iggyvolz\ClassProperties\Attributes\Getter;
use iggyvolz\ClassProperties\Attributes\Identifier;
use iggyvolz\ClassProperties\Attributes\Property;
use iggyvolz\ClassProperties\Identifiable;

/**
 * Example where the identifier is another identifiable
 * This does not allow 5 as an identifier
 * @property ExampleIdentifiable $id
 */
class ExampleRecursiveIdentifiable extends Identifiable
{
    // <<Property>>
    // <<Identifier>>
    protected ExampleIdentifiable $id;
    public static function getFromIdentifier($identifier): ?self
    {
        if(!$identifier instanceof ExampleIdentifiable) {
            $identifier = ExampleIdentifiable::getFromIdentifier($identifier);
        }
        if(is_null($identifier)) {
            return null;
        }
        if($identifier->getIdentifier() === 5) {
            return null;
        }
        $instance = new self();
        $instance->__set("id", $identifier);
        return $instance;
    }
}

(new Property)->addToProperty(ExampleRecursiveIdentifiable::class, "id");
(new Identifier)->addToProperty(ExampleRecursiveIdentifiable::class, "id");