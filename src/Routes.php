<?php
namespace Src;

use Closure;
use Src\Routes\Router as HttpRouter;
use Src\Core\Facade;
use Override;

/**
 * Summary of Router
 * @method static HttpRouter config(string $controllerNamespace,string $middlewareNamespace)
 * @method static HttpRouter get(string $path)
 * @method static HttpRouter post(string $path)
 * @method static HttpRouter put(string $path)
 * @method static HttpRouter delete(string $path)
 * @method static HttpRouter group(string $prefix,callable $callback,string|array $middleware = [])
 * @method static mixed dispatch()
 * @method static void cache()
 * @mixin  HttpRouter
 */
final class Routes extends Facade{
    #[Override]
    protected function getProcessClass(): string
    {
        return HttpRouter::class;
    }
}