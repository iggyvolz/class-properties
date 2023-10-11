<?php

declare(strict_types=1);

namespace iggyvolz\ClassProperties;

use Closure;
use Generator;
use iggyvolz\ClassProperties\Attributes\Getter;
use iggyvolz\ClassProperties\Attributes\Property;
use iggyvolz\ClassProperties\Attributes\Setter;
use iggyvolz\ClassProperties\Hooks\PostGet;
use iggyvolz\ClassProperties\Hooks\PostSet;
use iggyvolz\ClassProperties\Hooks\PreGet;
use iggyvolz\ClassProperties\Hooks\PreSet;
use iggyvolz\Initializable\Initializable;
use ReflectionClass;
use ReflectionAttribute;
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
     * @psalm-var array<
     *  class-string<self>,
     *  array<string,ReflectionProperty|Closure(ClassProperties):Closure(mixed):void>
     * >
     */
    private static array $setters = [];

    /**
     * @var array<string, array<string, PreGet[]>>
     * @psalm-var array<class-string<self>, array<string,list<PreGet>>>
     */
    private static array $preGetHooks = [];

    /**
     * @var array<string, array<string, PostGet[]>>
     * @psalm-var array<class-string<self>, array<string,list<PostGet>>>
     */
    private static array $postGetHooks = [];

    /**
     * @var array<string, array<string, PreSet[]>>
     * @psalm-var array<class-string<self>, array<string,list<PreSet>>>
     */
    private static array $preSetHooks = [];

    /**
     * @var array<string, array<string, PostSet[]>>
     * @psalm-var array<class-string<self>, array<string,list<PostSet>>>
     */
    private static array $postSetHooks = [];

    public static function init(): void
    {
        if (array_key_exists(static::class, self::$getters) && array_key_exists(static::class, self::$setters)) {
            return;
        }
        self::$setters[static::class] = [];
        self::$getters[static::class] = [];
        self::$preGetHooks[static::class] = [];
        self::$postGetHooks[static::class] = [];
        self::$preSetHooks[static::class] = [];
        self::$postSetHooks[static::class] = [];
        $reflection = new ReflectionClass(static::class);
        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            foreach (
                $property->getAttributes(
                    Property::class,
                    ReflectionAttribute::IS_INSTANCEOF
                ) as $attribute
            ) {
                $propertyInstance = $attribute->newInstance();
                if ($propertyInstance->CanRead) {
                    self::$getters[static::class][$property->getName()] = $property;
                }
                if ($propertyInstance->CanWrite) {
                    self::$setters[static::class][$property->getName()] = $property;
                }
            }
            // Gather pre/post-get/set hooks
            self::$preGetHooks[static::class][$property->getName()] = array_values(array_map(
                function (ReflectionAttribute $attr): PreGet {
                    $inst = $attr->newInstance();
                    if (!$inst instanceof PreGet) {
                        // TODO we know this must be a PreGet, but that typing is not available in static analysis
                        // @codeCoverageIgnoreStart
                        throw new \LogicException();
                        // @codeCoverageIgnoreEnd
                    }
                    return $inst;
                },
                $property->getAttributes(
                    PreGet::class,
                    ReflectionAttribute::IS_INSTANCEOF
                )
            ));
            self::$postGetHooks[static::class][$property->getName()] = array_values(array_map(
                function (ReflectionAttribute $attr): PostGet {
                    $inst = $attr->newInstance();
                    if (!$inst instanceof PostGet) {
                        // @codeCoverageIgnoreStart
                        throw new \LogicException();
                        // @codeCoverageIgnoreEnd
                    }
                    return $inst;
                },
                $property->getAttributes(
                    PostGet::class,
                    ReflectionAttribute::IS_INSTANCEOF
                )
            ));
            self::$preSetHooks[static::class][$property->getName()] = array_values(array_map(
                function (ReflectionAttribute $attr): PreSet {
                    $inst = $attr->newInstance();
                    if (!$inst instanceof PreSet) {
                        // @codeCoverageIgnoreStart
                        throw new \LogicException();
                        // @codeCoverageIgnoreEnd
                    }
                    return $inst;
                },
                $property->getAttributes(
                    PreSet::class,
                    ReflectionAttribute::IS_INSTANCEOF
                )
            ));
            self::$postSetHooks[static::class][$property->getName()] = array_values(array_map(
                function (ReflectionAttribute $attr): PostSet {
                    $inst = $attr->newInstance();
                    if (!$inst instanceof PostSet) {
                        // @codeCoverageIgnoreStart
                        throw new \LogicException();
                        // @codeCoverageIgnoreEnd
                    }
                    return $inst;
                },
                $property->getAttributes(
                    PostSet::class,
                    ReflectionAttribute::IS_INSTANCEOF
                )
            ));
        }
        foreach ($reflection->getMethods() as $method) {
            $method->setAccessible(true);
            foreach (
                $method->getAttributes(
                    Getter::class,
                    ReflectionAttribute::IS_INSTANCEOF
                ) as $attribute
            ) {
                $propertyInstance = $attribute->newInstance();
                self::$getters[static::class][$propertyInstance->PropertyName] =
                    function (self $self) use ($method): Closure {
                        $closure = $method->getClosure($self);
                        if (is_null($closure)) {
                            // @codeCoverageIgnoreStart
                            throw new \RuntimeException("Could not get closure for " . $method->getName());
                            // @codeCoverageIgnoreEnd
                        }
                        return /** @return mixed */ fn() => $closure();
                    };
            }
            foreach (
                $method->getAttributes(
                    Setter::class,
                    ReflectionAttribute::IS_INSTANCEOF
                ) as $attribute
            ) {
                $propertyInstance = $attribute->newInstance();
                self::$setters[static::class][$propertyInstance->PropertyName] = $method->getClosure(...);
            }
        }
    }

    /**
     * @return array<string,mixed>
     */
    public function __debugInfo(): array
    {
        return iterator_to_array((/** @return Generator<string,mixed> */ function (): Generator {
            foreach (self::$getters[static::class] as $name => $_) {
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
        foreach (self::$preSetHooks[static::class][$name] ?? [] as $preSetHook) {
            $preSetHook->runPreSetHook($this, $name, $value);
        }
        /** @var Closure|ReflectionProperty $getter */
        $setter = self::$setters[static::class][$name];
        if ($setter instanceof ReflectionProperty) {
            $setter->setValue($this, $value);
        } else {
            $setter($this)($value);
        }
        foreach (self::$postSetHooks[static::class][$name] ?? [] as $postSetHook) {
            $postSetHook->runPostSetHook($this, $name, $value);
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
        foreach (self::$preGetHooks[static::class][$name] ?? [] as $preGetHook) {
            $preGetHook->runPreGetHook($this, $name);
        }
        $getter = self::$getters[static::class][$name];
        if ($getter instanceof ReflectionProperty) {
            /** @psalm-var mixed $val */
            $val = $getter->getValue($this);
        } else {
            /** @psalm-var mixed $val */
            $val = $getter($this)();
        }
        foreach (self::$postGetHooks[static::class][$name] ?? [] as $postGetHook) {
            $postGetHook->runPostGetHook($this, $name, $val);
        }
        return $val;
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
