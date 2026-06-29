<?php
namespace Src\Routing\Definition;

use Src\Routing\Builder\PathBuilder;

class DefinitionRouter{
    public function __construct(
            public string $method = "GET",
            public ?string $name = null,
            public ?string $path = null,
            public ?string $pattern = null,
            public array $keys = [],
            public array $where = [],
            public array $controller = [],
            public array $middlewares = []
        ){}
    
        public function toArray():array{
            return [
            $this->method=>[
                $this->path =>[
                "METHOD"=>$this->method,
                "NAME"=>$this->name,
                "PATH"=>$this->path,
                "PATTERN"=>$this->pattern,
                "KEYS"=>$this->keys,
                "WHERE"=>$this->where,
                "CONTROLLER"=>$this->controller,
                "MIDDLEWARES"=>$this->middlewares
                ]
           ]
        ];
        }
    
}