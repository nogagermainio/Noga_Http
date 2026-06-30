<?php
namespace Src\Contracts\Router;

interface RouterInterface
{

    /**
     * Summary of get
     * @return static
     */
    public function get(string $path): static;

    /**
     * Summary of post
     * @return static
     */
    public function post(string $path): static;

    /**
     * Summary of put
     * @return static
     */
    public function put(string $path): static;

    /**
     * Summary of delete
     * @return static
     */
    public function delete(string $path): static;

    /**
     * Summary of name
     * @param string $name
     * @return static
     */
    public function name(string $name): static;

    /**
     * Summary of where
     * @param array $condition
     * @return static
     */
    public function where(array $condition): static;

    /**
     * Summary of controller
     * @param mixed $controller
     * @return static
     */
    public function controller(mixed $controller): static;

    /**
     * Summary of middleware
     * @param mixed $middleware
     * @return static
     */
    public function middleware(mixed $middleware): static;

    /**
     * Summary of globalMiddleware
     * @param mixed $middleware
     * @param string $method
     * @return void
     */
    public function globalMiddleware(mixed $middleware, string $method = "GET"): void;

    /**
     * Summary of group
     * @param string $prefix
     * @param callable $callback
     * @param mixed $middleware
     * @return static
     */
    public function group(string $prefix, callable $callback, mixed $middleware = []): static;

    /**
     * Summary of register
     * @return static
     */
    public function register(): static;

    /**
     * Summary of dispatch
     * @return mixed
     */
    public function dispatch():mixed;

    /**
     * Summary of cache
     * @return void
     */
    public function cache(): void;

}
