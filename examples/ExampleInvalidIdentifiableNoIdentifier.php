<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\examples;

use iggyvolz\ClassProperties\Identifiable;

/**
 * Invalid example for Identifiable - no identifier
 */
class ExampleInvalidIdentifiableNoIdentifier extends Identifiable
{
    /**
     * @param int|string|Identifiable $identifier
     * @return static|null
     */
    public static function getFromIdentifier($identifier): ?self
    {
        return null;
    }
}
