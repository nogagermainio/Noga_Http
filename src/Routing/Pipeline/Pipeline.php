<?php
namespace Src\Routing\Pipeline;

use Closure;
use Src\Container\Container;
use Src\Exceptions\ClassNotFoundException;


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
    private function executeMiddleware(mixed $middleware, array $params, array $data, Closure $next):mixed
    {

    if ($middleware instanceof Closure) {
        return $middleware($params,$data,$next);
    }
    
    if (\is_string($middleware) && function_exists($middleware)) {
        return $middleware($params,$data,$next);
    }

    if (\is_array($middleware)) {
        $class = $middleware[0] ?? "";
        $method = $middleware[1] ?? "";

        if (!class_exists($class)) {
            throw new ClassNotFoundException("Undefined class {$class}");
        }

        $instance = $this->container->get($class);

        return $method
            ? $instance->$method($params,$data,$next)
            : $instance($params,$data,$next);
    }

    // 4. invokable class
    if (\is_string($middleware) && class_exists($middleware)) {

        $instance = $this->container->get($middleware);

        if (is_callable($instance)) {
            return $instance($params,$data,$next);
        }
    }

    throw new \Src\Exceptions\ControllerHttpException("Invalid controller type");
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
        $class = $controller[0] ?? "";
        $method = $controller[1] ?? "";

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

    throw new \Src\Exceptions\ControllerHttpException("Invalid controller type");
}


}