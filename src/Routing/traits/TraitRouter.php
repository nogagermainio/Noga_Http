<?php declare(strict_types=1);
namespace Src\Routing\Traits;


use Src\Routing\Definition\Definition;
use Src\Routing\Definition\DefinitionType;
use Src\Routing\Parsing\Parse;

trait TraitRouter
{
    private array $groupStack    = [];
    private string $controllerNamespace = "";
    private string $middlewareNamespace = "";
    private ?Definition $definition = null;

    private function match(array $route, string $uri): array
    {          
        if (! preg_match($route['PATTERN'], $uri, $matches)) {
            return [false, []];
        }

        array_shift($matches);

        $params = [];
        $keys   = $route['KEYS'] ?? [];

        $matches = \array_slice(
            $matches,
            0,
            \count($keys)
        );

        // security anti crash
        if (\count($keys) !== \count($matches)) {     
            return [false, []];
        }

        $params = $keys ? array_combine($keys, $matches) : [];
       
        return [true, $params];
    }

    private function normalizePath(string $path): string
    {
        $paths = '';
        if (str_contains($path, '.')) {
            $paths = explode('.', trim($path, '/'));
            $paths = implode('/', $paths);

        } else {
            $paths = trim($path, '/');
        }

        return $paths === '/' ? '/' : "/$paths";
    }

    private function buildPath(string $path): string
    {
        $prefix = '';
        $paths  = '';
        foreach ($this->groupStack as $group) {
            $prefix .= $group['prefix'];
        }

        $paths = "$prefix/$path";

        return $this->normalizePath($paths);
    }

    private function normalizeController(array $controller): Definition
    {
        return $this->parse(
            $controller,
            $this->controllerNamespace
            );
    }

    private function normalizeMiddleware(array $middleware): Definition
    {
     
      return $this->parse(
            $middleware,
            $this->middlewareNamespace
        );
    }

    private function resolveDefinition(array $definition,mixed $runtime):mixed{
        $define = [];
        foreach($definition as $def){
         $define = \is_array($def) ? $def : $definition;   

        if($define['type'] ===  DefinitionType::Closure){
            return $runtime;
        }elseif($define['type'] === DefinitionType::Null){
            return null;
        }

        return $define['execute'];
          
        }

        return null;
       
    }

    private function collectGroupMiddleware(): array
    {
        $middleware = [];
        foreach ($this->groupStack as $group) {
            $middleware[] = $group['middleware'];
        }

        return $middleware;
    }

    private function parse(array $data,string $namespaces):Definition{
        $this->definition = (new Parse($data,$namespaces))->handle();
        return $this->definition;
    }
}
