<?php declare(strict_types=1);
namespace Src\Traits\Router;

use Closure;
use Src\Routes\Definition\Definition;
use Src\Routes\Definition\DefinitionType;
use Src\Routes\Definition\Parse;

trait TraitRouter
{
    private array $groupStack    = [];
    private string $controllerNamespace = "";
    private string $middlewareNamespace = "";
    private ?Definition $definition = null;
    private string $regexRoutes = "#\{(\w+)(?::([^}]+))?\}#";
    private string $defaultRegex = "([^/]+)";

    private function match(array $route, string $uri): array
    {

        if (! preg_match($route['PATTERN'], $uri, $matches)) {
            return [false, []];
        }

        array_shift($matches);

        $params = [];
        $keys   = $route['KEYS'];

        $keys = $keys ?? [];

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

    private function getPattern(array $route): array
    {
        preg_match_all(
            $this->regexRoutes,
            $route['PATH'],
            $keys
        );

        $pattern = preg_replace_callback(
             $this->regexRoutes,
            function ($m) use ($route) {
                $param = $m[1];

                if (isset($route["WHERE"][$param])) {
                    return "({$route['WHERE'][$param]})";
                }

                return isset($m[2]) ? "({$m[2]})" : $this->defaultRegex;
            },
            $route['PATH']
        );

        return [
            "pattern" => "#^$pattern$#",
            "keys"    => $keys[1] ?? [],
        ];
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
         
        if($definition['type'] ===  DefinitionType::Closure){
            return $runtime;
        }

        return $definition['execute'];
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
