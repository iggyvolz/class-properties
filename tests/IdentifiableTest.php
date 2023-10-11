<?php

namespace iggyvolz\ClassProperties\tests;

use PHPUnit\Framework\TestCase;
use iggyvolz\ClassProperties\Identifiable;
use iggyvolz\ClassProperties\examples\ExampleIdentifiable;
use iggyvolz\ClassProperties\examples\ExampleRecursiveIdentifiable;
use iggyvolz\ClassProperties\examples\ExampleInvalidUntypedIdentifiable;
use iggyvolz\ClassProperties\examples\ExampleInvalidIdentifiableNoIdentifier;
use iggyvolz\ClassProperties\examples\ExampleInvalidIdentifiableDuplicateIdentifier;

class IdentifiableTest extends TestCase
{
    public function testGettingIdentifiable()
    {
        $identifiableObject = ExampleIdentifiable::getFromIdentifierForced(1);
        $this->assertSame(2, $identifiableObject->idPlusOne);
        $this->assertSame(1, $identifiableObject->getIdentifier());
        $this->assertSame(1, $identifiableObject->id);
    }

    public function testGettingIdentifiableError()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Could not find object " . ExampleIdentifiable::class . ":2");
        ExampleIdentifiable::getFromIdentifierForced(2);
    }

    public function testGettingRecursiveIdentifiable()
    {
        $identifiableObject = ExampleIdentifiable::getFromIdentifierForced(1);
        $exampleRecursive = ExampleRecursiveIdentifiable::getFromIdentifierForced($identifiableObject);
        $this->assertSame(1, $exampleRecursive->getIdentifier());
        $this->assertSame($identifiableObject, $exampleRecursive->id);
    }

    public function testGettingRecursiveIdentifiableError()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Could not find object " . ExampleRecursiveIdentifiable::class . ":8");
        ExampleRecursiveIdentifiable::getFromIdentifierForced(8);
    }

    public function testGettingRecursiveIdentifiableFailure()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Could not find object " . ExampleRecursiveIdentifiable::class . ":5");
        ExampleRecursiveIdentifiable::getFromIdentifierForced(ExampleIdentifiable::getFromIdentifierForced(5));
    }

    public function testUntypedIdentifiable()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Invalid identifier id for " . ExampleInvalidUntypedIdentifiable::class
            . ": Property should be one or more of: int, string, or Identifiable");
        ExampleInvalidUntypedIdentifiable::init();
    }

    public function testNoIdentifierIdentifiable()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("No identifier found for " . ExampleInvalidIdentifiableNoIdentifier::class);
        ExampleInvalidIdentifiableNoIdentifier::init();
    }

    public function testDuplicateIdentifier()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(
            "Duplicate identifier for " . ExampleInvalidIdentifiableDuplicateIdentifier::class
            . ": found id1 and id2"
        );
        ExampleInvalidIdentifiableDuplicateIdentifier::init();
    }

    public function testNoIdentifierCheckOnAbstractClass()
    {
        $this->assertSame(1, 1); // No exception should be thrown
        Identifiable::init();
    }
}
