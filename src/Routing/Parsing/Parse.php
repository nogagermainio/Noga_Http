<?php
namespace Src\Routing\Parsing;

use Closure;
use Src\Exceptions\ClassMethodUndefined;
use Src\Exceptions\ClassNotFoundException;
use Src\Routing\Definition\Definition;
use Src\Routing\Definition\DefinitionType;

class Parse
{
    public function __construct(
        private array $data,
        private string $namespaces
    ) {}

    /**
     * Summary of handle
     * @return array|Definition
     */
    public function handle(): Definition
    {
        $data = [];
        foreach($this->data as $d){
        $data = match (true) {

            $d === null =>
                $this->parseDefine(DefinitionType::Null),

            \is_string($d) =>
                $this->parseString($d),

            \is_array($d) =>
                $this->parseClassMethod($d),

            $d instanceof Closure =>
                $this->parseClosure($d),

            \is_object($d) && is_callable($d) =>
                $this->parseInvokable($d),

            default =>
                $this->parseDefine(DefinitionType::Null),
        };

        }

        return  $data;
    }

    /**
     * Closure
     *
     * Summary of parseClosure
     * @param Closure $closure
     * @return Definition
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
     *
     * Summary of parseInvokable
     * @param object $controller
     * @return Definition
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
     *
     * Summary of parseFunction
     * @param string $function
     * @return Definition
     */
    private function parseFunction(string $function): Definition
    {
        return $this->parseDefine(
            type: DefinitionType::Function,
            execute: $function
        );
    }

    /**
     * [Controller::class,'method']
     * 
     * Summary of parseClassMethod
     * @param array $value
     * @throws ClassNotFoundException
     * @throws ClassMethodUndefined
     * @return Definition
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
     * Summary of parseSpecialValues
     * @param string $value
     * @throws ClassNotFoundException
     * @throws ClassMethodUndefined
     * @return Definition
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
     * Summary of parseString
     * @param string $value
     * @return Definition
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

    /**
     * Summary of parseDefine
     * @param DefinitionType $type
     * @param array|string|null $execute
     * @param mixed $runtime
     * @return Definition
     */
    private function parseDefine(
        DefinitionType $type,
        array|string|null $execute = null,
        ?callable $runtime = null
    ): Definition {

        return new Definition(
            $type,
            $execute,
            $runtime
        );
    }
}