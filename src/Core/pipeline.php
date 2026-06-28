<?php

namespace Src\Core;

use Closure;
use Src\Exception\ClassNotFoundException;


class Pipeline
{
    /**
     * Summary of __construct
     * @param array $middleware
     * @param array|callable $controller
     * @param Container $container
     */
    public function __construct(
        private array $middleware,
        private mixed $controller,
        private Container $container
    ) {}

    /**
     * Summary of run
     * @param array $params
     * @param array $data
     */
   public function run(array $params, array $data): mixed
{
    $core = fn() => $this->executeController($params, $data);

    $pipeline = array_reduce(
        array_reverse($this->middleware),
        function ($next, $middleware) use ($params, $data) {
            return function () use ($middleware, $params, $data, $next) {
                return $this->executeMiddleware($middleware, $params, $data, $next);
            };
        },
        $core
    );

    return $pipeline();
}

    /**
     * Summary of executeMiddleware
     * @param mixed $middleware
     * @param array $params
     * @param array $data
     * @param Closure $next
     */
    private function executeMiddleware(array|Closure $middleware, array $params, array $data, Closure $next):mixed
    {
        
        if (\is_array($middleware)) {

            [$class, $method] = $middleware;

            $instance = $this->container->get($class);

            return $instance->$method($params, $data, $next);
        }

        return $middleware($params,$data,$next);
    }

    /**
     * Summary of executeController
     * @param array $params
     * @param array $data
     */
 private function executeController(array $params, array $data): mixed
{
    $args = $params ? array_values($params) : [$data];

    $controller = $this->controller;

    if ($controller instanceof Closure) {
        return $controller(...$args);
    }

    if (\is_string($controller) && function_exists($controller)) {
        return $controller(...$args);
    }

    if (\is_array($controller)) {

        [$class, $method] = $controller;

        if (!class_exists($class)) {
            throw new ClassNotFoundException("Undefined class {$class}");
        }

        $instance = $this->container->get($class);

        return $method
            ? $instance->$method(...$args)
            : $instance(...$args);
    }

    // 4. invokable class
    if (\is_string($controller) && class_exists($controller)) {

        $instance = $this->container->get($controller);

        if (is_callable($instance)) {
            return $instance(...$args);
        }
    }

    throw new \Src\Exception\ControllerHttpException("Invalid controller type");
}

}