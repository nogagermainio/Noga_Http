<?php

namespace Src\Routes\Definition;

use Closure;
use Src\Exception\ClassMethodUndefined;
use Src\Exception\ClassNotFoundException;

class Parse
{
    public function __construct(
        private array $data,
        private string $namespaces
    ) {}

    public function handle(): Definition
    {
        $d = $this->data[0] ?? null;
        
        return match (true) {

            $d === null =>
                $this->parseDefine(DefinitionType::Null),

            is_string($d) =>
                $this->parseString($d),

            is_array($d) =>
                $this->parseClassMethod($d),

            $d instanceof Closure =>
                $this->parseClosure($d),

            is_object($d) && is_callable($d) =>
                $this->parseInvokable($d),

            default =>
                $this->parseDefine(DefinitionType::Null),
        };
    }

    /**
     * Closure
     */
    private function parseClosure(Closure $closure): Definition
    {
        return $this->parseDefine(
            type: DefinitionType::Closure,
            runtime: $closure
        );
    }

    /**
     * Invokable object
     */
    private function parseInvokable(object $controller): Definition
    {
        return $this->parseDefine(
            type: DefinitionType::Invokable,
            execute: [$controller::class]
        );
    }

    /**
     * Global function
     */
    private function parseFunction(string $function): Definition
    {
        return $this->parseDefine(
            type: DefinitionType::Function,
            execute: [$function]
        );
    }

    /**
     * [Controller::class,'method']
     */
    private function parseClassMethod(array $value): Definition
    {
        $class  = $value[0] ?? "";
        $method = $value[1] ?? "";

        if (!class_exists($class)) {
            throw new ClassNotFoundException("Class {$class} is undefined.");
        }

        if ($method === "") {

            if (method_exists($class, '__invoke')) {

                return $this->parseDefine(
                    type: DefinitionType::Invokable,
                    execute: [$class]
                );
            }

            return $this->parseDefine(
                type: DefinitionType::Classes,
                execute: [$class]
            );
        }

        if (!method_exists($class, $method)) {
            throw new ClassMethodUndefined(
                "Method {$method} not found in {$class}"
            );
        }

        return $this->parseDefine(
            type: DefinitionType::ClassMethod,
            execute: [$class, $method]
        );
    }

    /**
     * "Controller.index"
     */
    private function parseSpecialValues(string $value): Definition
    {
        if (!str_contains($value, '.')) {
            return $this->parseDefine(DefinitionType::Null);
        }

        [$class, $method] = explode('.', $value, 2);

        $class = trim($this->namespaces, '\\') . "\\{$class}";

        if (!class_exists($class)) {
            throw new ClassNotFoundException("Class {$class} is undefined.");
        }

        if (!method_exists($class, $method)) {
            throw new ClassMethodUndefined(
                "Method {$method} not found in {$class}"
            );
        }

        return $this->parseDefine(
            type: DefinitionType::ClassMethod,
            execute: [$class, $method]
        );
    }

    /**
     * String parser
     */
    private function parseString(string $value): Definition
    {
        if (function_exists($value)) {
            return $this->parseFunction($value);
        }

        if (class_exists($value)) {

            if (method_exists($value, '__invoke')) {

                return $this->parseDefine(
                    type: DefinitionType::Invokable,
                    execute: [$value]
                );
            }

            return $this->parseDefine(
                type: DefinitionType::Classes,
                execute: [$value]
            );
        }

        return $this->parseSpecialValues($value);
    }

    private function parseDefine(
        DefinitionType $type,
        array $execute = [],
        ?callable $runtime = null
    ): Definition {

        return new Definition(
            $type,
            $execute,
            $runtime
        );
    }
}