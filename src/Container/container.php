<?php declare(strict_types=1);
namespace Src\Container;

use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;

class Container
{
    private array $instances = [];
    private array $bindings  = [];

    /**
     * Bind interface → implementation
     */
    public function bind(string $abstract, string $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * Singleton getter
     */
    public function get(?string $abstract): object
    {
        $concrete = $this->bindings[$abstract] ?? $abstract;

        if (!class_exists($concrete)) {
            throw new ReflectionException("Unresolvable class: {$abstract} → {$concrete}");
        }

        if (isset($this->instances[$concrete])) {
            return $this->instances[$concrete];
        }

        return $this->instances[$concrete] = $this->resolve($concrete);
    }

    /**
     * Resolve dependencies recursively
     */
    private function resolve(string $class): object
    {
        if (!class_exists($class)) {
            throw new ReflectionException("Class not found: {$class}");
        }

        $reflector = new ReflectionClass($class);

        if (!$reflector->isInstantiable()) {
            throw new ReflectionException("Class not instantiable: {$class}");
        }

        $constructor = $reflector->getConstructor();

        if (!$constructor) {
            return new $class();
        }

        $dependencies = [];

        foreach ($constructor->getParameters() as $param) {

            $type = $param->getType();

            if (!$type instanceof ReflectionNamedType) {
                throw new ReflectionException(
                    "Unsupported parameter type in {$class}::\${$param->getName()}"
                );
            }

            // Primitive types
            if ($type->isBuiltin()) {

                if ($param->isDefaultValueAvailable()) {
                    $dependencies[] = $param->getDefaultValue();
                    continue;
                }

                throw new ReflectionException(
                    "Cannot resolve primitive \${$param->getName()} in {$class}"
                );
            }

            $dependencyClass = $type->getName();

            $dependencies[] = $this->get($dependencyClass);
        }

        return $reflector->newInstanceArgs($dependencies);
    }
}