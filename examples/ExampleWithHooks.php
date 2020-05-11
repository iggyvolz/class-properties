<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties\examples;

use iggyvolz\ClassProperties\Attributes\Property;
use iggyvolz\ClassProperties\ClassProperties;

/**
 * @property int $preGetHook
 * @property int $postGetHook
 * @property int $preSetHook
 * @property int $postSetHook
 */
class ExampleWithHooks extends ClassProperties
{
    // <<Property>>
    // <<ExamplePreGetHook>>
    protected int $preGetHook = 1;
    // <<Property>>
    // <<ExamplePostGetHook>>
    protected int $postGetHook = 2;
    // <<Property>>
    // <ExamplePreSetHook>>
    protected int $preSetHook = 3;
    // <<Property>>
    // <<ExamplePostSetHook>>
    protected int $postSetHook = 4;
}

(new Property())->addToProperty(ExampleWithHooks::class, "preGetHook");
(new ExamplePreGetHook())->addToProperty(ExampleWithHooks::class, "preGetHook");
(new Property())->addToProperty(ExampleWithHooks::class, "postGetHook");
(new ExamplePostGetHook())->addToProperty(ExampleWithHooks::class, "postGetHook");
(new Property())->addToProperty(ExampleWithHooks::class, "preSetHook");
(new ExamplePreSetHook())->addToProperty(ExampleWithHooks::class, "preSetHook");
(new Property())->addToProperty(ExampleWithHooks::class, "postSetHook");
(new ExamplePostSetHook())->addToProperty(ExampleWithHooks::class, "postSetHook");
