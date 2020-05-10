<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties;

use Closure;
use Generator;
use iggyvolz\ClassProperties\Attributes\Getter;
use iggyvolz\ClassProperties\Attributes\Property;
use iggyvolz\ClassProperties\Attributes\Setter;
use iggyvolz\Initializable\Initializable;
use iggyvolz\virtualattributes\ReflectionAttribute;
use iggyvolz\virtualattributes\VirtualAttribute;
use ReflectionClass;
//use ReflectionAttribute;
use ReflectionProperty;

abstract class ClassProperties implements Initializable
{
    /**
     * @var array<string, array<string,ReflectionProperty|Closure>>
     * @psalm-var array<class-string<self>, array<string, ReflectionProperty|Closure(ClassProperties):Closure():mixed>>
     */
    private static array $getters = [];
    /**
     * @var array<string, array<string,ReflectionProperty|Closure>>
     * @psalm-var array<class-string,array<string, ReflectionProperty|Closure(ClassProperties):Closure(mixed):void>>
     */
    private static array $setters = [];
    public static function init(): void
    {
        if (array_key_exists(static::class, self::$getters) && array_key_exists(static::class, self::$setters)) {
            return;
        }
        self::$setters[static::class] = [];
        self::$getters[static::class] = [];
        $reflection = new ReflectionClass(static::class);
        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            foreach (
                VirtualAttribute::getAttributes(
                    $property,
                    Property::class,
                    ReflectionAttribute::IS_INSTANCEOF
                ) as $attribute
            ) {
                /** @var Property $propertyInstance */
                $propertyInstance = $attribute->newInstance();
                if ($propertyInstance->CanRead) {
                    self::$getters[static::class][$property->getName()] = $property;
                }
                if ($propertyInstance->CanWrite) {
                    self::$setters[static::class][$property->getName()] = $property;
                }
            }
        }
        foreach ($reflection->getMethods() as $method) {
            $method->setAccessible(true);
            foreach (
                VirtualAttribute::getAttributes(
                    $method,
                    Getter::class,
                    ReflectionAttribute::IS_INSTANCEOF
                ) as $attribute
            ) {
                /** @var Getter $propertyInstance */
                $propertyInstance = $attribute->newInstance();
                self::$getters[static::class][$propertyInstance->PropertyName] =
                    function (self $self) use ($method): Closure {
                        $closure = $method->getClosure($self);
                        if (is_null($closure)) {
                            throw new \RuntimeException("Could not get closure for " . $method->getName());
                        }
                        return /** @return mixed */fn() => $closure();
                    };
            }
            foreach (
                VirtualAttribute::getAttributes(
                    $method,
                    Setter::class,
                    ReflectionAttribute::IS_INSTANCEOF
                ) as $attribute
            ) {
                /** @var Setter $propertyInstance */
                $propertyInstance = $attribute->newInstance();
                self::$setters[static::class][$propertyInstance->PropertyName] =
                    function (self $self) use ($method): Closure {
                        $closure = $method->getClosure($self);
                        if (is_null($closure)) {
                            throw new \RuntimeException("Could not get closure for " . $method->getName());
                        }
                        return /** @param mixed $val */function ($val) use ($closure): void {
                            $closure($val);
                        };
                    };
            }
        }
    }

    /**
     * @return array<string,mixed>
     */
    public function __debugInfo(): array
    {
        return iterator_to_array((/** @return Generator<string,mixed> */function (): Generator {
            foreach (self::$getters[static::class] as $name => $property) {
                yield $name => $this->__get($name);
            }
        })());
    }

    /**
     * @param mixed $value
     */
    public function __set(string $name, /* mixed */ $value): void
    {
        static::init();
        if (!array_key_exists($name, self::$setters[static::class])) {
            throw new \InvalidArgumentException("Invalid property access $name on " . static::class);
        }
        /** @var Closure|ReflectionProperty $getter */
        $setter = self::$setters[static::class][$name];
        if ($setter instanceof ReflectionProperty) {
            $setter->setValue($this, $value);
        } else {
            $setter($this)($value);
        }
    }

    /**
     * @return mixed
     */
    public function __get(string $name)/* :mixed */
    {
        static::init();
        if (!array_key_exists($name, self::$getters[static::class])) {
            throw new \InvalidArgumentException("Invalid property access $name on " . static::class);
        }
        $getter = self::$getters[static::class][$name];
        if ($getter instanceof ReflectionProperty) {
            return $getter->getValue($this);
        } else {
            return $getter($this)();
        }
    }

    public function __isset(string $name): bool
    {
        static::init();
        if (!array_key_exists($name, self::$getters[static::class])) {
            throw new \InvalidArgumentException("Invalid property access $name on " . static::class);
        }
        /** @var Closure|ReflectionProperty $getter */
        $getter = self::$getters[static::class][$name];
        if ($getter instanceof ReflectionProperty) {
            return boolval($getter->isInitialized($this));
        } else {
            return true;
        }
    }

    public function __unset(string $name): void
    {
        throw new \LogicException("Invalid unset on " . static::class);
    }
}
