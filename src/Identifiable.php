<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties;

use iggyvolz\ClassProperties\Attributes\Identifier;
use iggyvolz\virtualattributes\ReflectionAttribute;
use iggyvolz\virtualattributes\VirtualAttribute;

/**
 * Class with an <<Identifier>> attribute
 */
abstract class Identifiable extends ClassProperties
{
    public function __construct()
    {
        static::init();
    }

    public static function init(): void
    {
        static::getIdentifierName();
        parent::init();
    }

    /**
     * @var \ReflectionProperty[]
     */
    private static array $identifiers = [];
    public static function getIdentifierName(): string
    {
        if (array_key_exists(static::class, self::$identifiers)) {
            return self::$identifiers[static::class]->getName();
        }
        $refl = new \ReflectionClass(static::class);
        /** @var \ReflectionProperty|null $identifier */
        $identifier = null;
        foreach ($refl->getProperties() as $property) {
            if (
                !empty(VirtualAttribute::getAttributes(
                    $property,
                    Identifier::class,
                    ReflectionAttribute::IS_INSTANCEOF
                ))
            ) {
                if (is_null($identifier)) {
                    $identifier = $property;
                } else {
                    throw new \LogicException(
                        "Duplicate identifier for " . static::class
                        . ": found " . $identifier->getName() . " and " . $property->getName()
                    );
                }
            }
        }
        if (is_null($identifier)) {
            throw new \LogicException("No identifier found for " . static::class);
        }
        if (!self::checkValidIdentifier($identifier)) {
            throw new \LogicException(
                "Invalid identifier " . $identifier->getName() . " for " . static::class
                . ": Property should be one or more of: int, string, or Identifiable"
            );
        }
        $identifier->setAccessible(true);
        self::$identifiers[static::class] = $identifier;
        return $identifier->getName();
    }
    private static function checkValidIdentifier(\ReflectionProperty $prop): bool
    {
        $type = $prop->getType();
        if (is_null($type)) {
            return false;
        }
        if (!$type instanceof \ReflectionNamedType) {
            // TODO: Allow multiple types in PHP8
            // @codeCoverageIgnoreStart
            return false;
            // @codeCoverageIgnoreEnd
        }
        if ($type->isBuiltin()) {
            return $type->getName() === "string" || $type->getName() === "int";
        } else {
            return is_subclass_of($type->getName(), Identifiable::class);
        }
    }

    /**
     * @param int|string|Identifiable $identifier
     * @return static|null
     */
    abstract public static function getFromIdentifier($identifier): ?self;
    /**
     * @param int|string|Identifiable $identifier
     * @return static
     * @phan-suppress PhanAbstractStaticMethodCallInStatic
     */
    public static function getFromIdentifierForced($identifier): self
    {
        if ((new \ReflectionClass(static::class))->isAbstract()) {
            throw new \LogicException("Cannot call getFromIdentifier on an abstract class");
        }
        $result = static::getFromIdentifier($identifier);
        if (is_null($result)) {
            if ($identifier instanceof Identifiable) {
                throw new \RuntimeException(
                    "Could not find object " . static::class . ":" . $identifier->getIdentifier()
                );
            } else {
                throw new \RuntimeException("Could not find object " . static::class . ":$identifier");
            }
        } else {
            return $result;
        }
    }

    /**
     * @return int|string
     */
    public function getIdentifier()
    {
        static::init();
        $identifier = self::$identifiers[static::class];
        /** @var int|string|Identifiable $value */
        $value = $identifier->getValue($this);
        if ($value instanceof Identifiable) {
            return $value->getIdentifier();
        }
        return $value;
    }
}
