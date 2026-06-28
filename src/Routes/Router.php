<?php declare(strict_types=1);
/**
 * Author : ZAFININGELY Noga Germainio
 * Mail : nogagerma122@gmail.com
 * Created at : June 2026 
 * Title : Router Http
 * 
 */
namespace Src\Routes;

use Closure;
use Src\Core\CacheManager;
use Src\Core\Container;
use Src\Exception\ExceptionHttpHandle;
use Src\Request\Request;
use Src\Interfaces\Router\RouterInterface;
use Src\Core\Pipeline;
use Src\Exception\ControllerHttpException;
use Src\Exception\HttpException;
use Src\Exception\NotFoundHttpException;
use Throwable;

class Router implements RouterInterface
{
  use \Src\Traits\Router\TraitRouter;

    private array $routes = [];

    private array $currentRoutes = [];

    private array $runtimeController = [];

    private array $runtimeMiddlewares = [];

    private array $globalMiddleware = [];

    private array $listRoutes = [];

    private array $where = [];

    private array $name = [];

    private string $method = "";

    private string $path = "";

    private ExceptionHttpHandle $exceptionHttpHandle;

    private ?Container $container = null;

    private ?CacheManager $cache = null;

    private ?Request $request = null;

    public function __construct()
    {
        $this->controllerNamespace = "Test";
        $this->middlewareNamespace = "App\\Middleware";
        $this->container = new Container();
        $this->exceptionHttpHandle = new ExceptionHttpHandle();
    }

    /**
     * Summary of RouteConfig
     * @param string $controllerNamespace
     * @param string $middlewareNamespace
     * @return static
     */
    public function config(string $controllerNamespace,string $middlewareNamespace):static{  
       $this->controllerNamespace = $controllerNamespace;
       $this->middlewareNamespace = $middlewareNamespace;

        return $this;
    } 

    /**
     * Summary of add
     * @param string $method
     * @param string $path
     * @return static
     */
    private function add(string $method, string $path): static
    {
        $this->method = $method;
        $this->path   = $path;
        
        $this->currentRoutes = [
            "METHOD"      => $method,
            "NAME"        => null,
            "PATH"        => $this->buildPath($path),
            "CONTROLLER"  => null,
            "MIDDLEWARES" => [],
            "WHERE"       => [],
            "PATTERN"     => null,
            "KEYS"        => null,
        ];

        return $this;
    }

    /**
     * Summary of get
     * @param string $path
     * @return static
     */
    public function get(string $path): static
    {
        return $this->add("GET", $path);
    }

    /**
     * Summary of post
     * @param string $path
     * @return static
     */
    public function post(string $path): static
    {
        return $this->add("POST", $path);
    }

    /**
     * Summary of put
     * @param string $path
     * @return static
     */
    public function put(string $path): static
    {
        $this->add("PUT", $path);
        return $this;
    }

    /**
     * Summary of delete
     * @param string $path
     * @return static
     */
    public function delete(string $path): static
    {
        return $this->add("DELETE", $path);
    }

    /**
     * Summary of name
     * @param string $name
     * @return static
     */
    public function name(string $name): static
    {
        $this->currentRoutes['NAME'] = $name;
        $this->register();
        return $this;
    }

    /**
     * Summary of where
     * @param array $condition
     * @return static
     */
    public function where(array $condition): static
    {
        $this->where                  = $condition;
        $this->currentRoutes['WHERE'] = $condition;
        return $this;
    }

    /**
     * Summary of controller
     * @param mixed $controller
     * @return static
     */
    public function controller(mixed $controller): static
    {
        $this->currentRoutes['CONTROLLER'] = $controller;
        return $this;
    }

    /**
     * Summary of middleware
     * @param mixed $middleware
     * @return static
     */
    public function middleware(mixed $middleware): static
    {
        $this->currentRoutes['MIDDLEWARES'] = array_merge(
            $this->currentRoutes['MIDDLEWARES'] ?? [],
            [$middleware]
        );
        return $this;
    }

    /**
     * Summary of globalMiddleware
     * @param array|string $middleware
     * @param string $method
     * @return void
     */
    public function globalMiddleware(mixed $middleware, string $method = "ALL"): void
    {
        $middlewares = \is_array($middleware) ? $middleware : [$middleware];
        $this->globalMiddleware[$method][] = $this->normalizeMiddleware($middlewares)->toArray();
    }

    /**
     * Summary of group
     * @param string $prefix
     * @param callable $callback
     * @param mixed $middleware
     * @return static
     */
    public function group(string $prefix, callable $callback, mixed $middleware = []):static
    {
        $middlewares        = \is_string($middleware) ? [$middleware] : $middleware;
        $this->groupStack[] = [
            "prefix"     => $prefix,
            "middleware" => $middlewares,
        ];

        $callback($this);

        array_pop($this->groupStack);

        return $this;
    }

    /**
     * Summary of register
     * @return static
     */
    public function register(): static
    {
        
        $method = $this->currentRoutes["METHOD"];
        $path   = $this->currentRoutes['PATH'];

        if (empty($this->currentRoutes['CONTROLLER'])) {
           throw new ControllerHttpException("No controller in route : method : {$method} path : {$path} ");
        }

        $controller = $this->normalizeController([$this->currentRoutes['CONTROLLER']])->toArray();

        $this->runtimeController[$path] = $this->currentRoutes['CONTROLLER'] instanceof Closure ?
        $this->currentRoutes['CONTROLLER'] : null;

        $this->name[$path] = $this->currentRoutes['NAME'];

        $this->currentRoutes['MIDDLEWARES'] = \array_merge(
            $this->currentRoutes['MIDDLEWARES'] ?? [],
            $this->collectGroupMiddleware()
        );
        $middlewares = [];
        $middlewares = array_merge(
            $middlewares,
            $this->normalizeMiddleware($this->currentRoutes['MIDDLEWARES'])->toArray() ?? []
        );

        $pattern                        = $this->getPattern($this->currentRoutes);
        $this->currentRoutes['KEYS']    = $pattern['keys'];
        $this->currentRoutes['PATTERN'] = $pattern['pattern'];

        $this->routes[$method][$path] = $this->currentRoutes;
        $this->routes[$method][$path]['CONTROLLER'] = $controller;
        $this->routes[$method][$path]['MIDDLEWARES'] = $middlewares;

        $this->currentRoutes          = [];
        return $this;
    }


    public function dispatch():mixed
    {
       
        $req    = $this->req();
        $method = $req->method();
        $uri    = $req->uri();
        $data   = $req->all();
        try{
          
        $cache  = $this->loadCache() ?? [];
        $this->routes = (isset($cache['data']) && ! empty($cache['data'])) ? $cache['data'] : $this->routes;
        $this->listRoutes[] = $this->routes;

        foreach ($this->routes[$method] ?? [] as $route) {
            [$ok, $params] = $this->match($route, $uri);

            if (! $ok) {
                continue;
            }

            $req->add_name(
                !empty($this->name[$uri]) ? $this->name[$uri] : '',
                $this->listRoutes
            );

            $globalsMiddleware = array_merge(
                $this->globalMiddleware['ALL'] ?? [],
                $this->globalMiddleware[$method] ?? [],
            );

            $middleware = array_merge(
                $globalsMiddleware ?? [],
                $route['MIDDLEWARES'] ?? []
            );
            

                $pipeline = new Pipeline(
                    $middleware,
                    $this->resolveDefinition(
                        $route['CONTROLLER'],
                        !empty($this->runtimeController[$uri]) ? $this->runtimeController[$uri] : null),
                    $this->container
                );

                $result = $pipeline->run($params, $data);
                return $result;  
        }

            throw new NotFoundHttpException("Page Not Found {$uri} on this site");

        } catch (Throwable $e) {
            return $this->exceptionHttpHandle->handle($e);
        }
    }

    /**
     * Summary of list
     * @return array
     */
    public function list(): array
    {
        return $this->routes;
    }

    /**
     * Summary of cache
     * @return void
     */
    public function cache(): void
    {
        if (! $this->ch()->hasValidSignature($this->routes)) {
            $this->ch()->signature($this->routes)
                ->data($this->routes)
                ->put();
        }
    }

    /**
     * Summary of loadCache
     * @return array
     */
    private function loadCache(): array
    {
        return $this->ch()->get() ?? [];
    }

    /**
     * Summary of ch
     * @return CacheManager
     */
    private function ch(): CacheManager
    {
        return $this->cache ??= CacheManager::key("router")
            ->dir('router');
    }

    /**
     * Summary of req
     * @return Request|null
     */
    private function req(): Request
    {
        return $this->request ??= Request::getInstance();
    }

}
