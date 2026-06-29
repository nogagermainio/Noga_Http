<?php
namespace Src\Routing\Builder;

use Src\Routing\Definition\DefinitionRouter;

class Builder{

    private array $routes = [];

    public array $controller = [];

    public array $middlewares = [];

    public array $globalMiddleware = [];

    public array $listRoutes = [];

    public array $where = [];

    public string $name = "";

    public string $method = "";

    public string $path = "";
    public string $pattern = "";
    public array $keys = [];
    public function __construct(){
        
    }

    public function build():static{
        $this->routes = (new DefinitionRouter(
            $this->method,
            $this->name,
            $this->path,
            $this->pattern,
            $this->keys,
            $this->where,
            $this->controller,
            array_merge(
            $this->globalMiddleware,
            $this->middlewares)
        ))->toArray();

        return $this;
    }

}