<?php

namespace Src\Routes\Tree;

class RouteTreeBuilder
{
    public function build(array $routes): RouteNode
    {
        $root = new RouteNode();

        foreach ($routes as $path => $route) {
            $this->insert($root, $path, $route);
        }

        return $root;
    }

    private function insert(RouteNode $root, string $path, array $route): void
    {
        $segments = array_values(array_filter(explode('/', trim($path, '/'))));

        $current = $root;

        foreach ($segments as $segment) {

            if ($this->isParam($segment)) {

                $key = '*';

                if (!isset($current->children[$key])) {
                    $node = new RouteNode();
                    $node->isParam = true;
                    $node->paramName = trim($segment, '{}');

                    if (!empty($route['WHERE'][$node->paramName] ?? null)) {
                        $node->regex = $route['WHERE'][$node->paramName];
                    }

                    $current->children[$key] = $node;
                }

                $current = $current->children[$key];

            } else {

                if (!isset($current->children[$segment])) {
                    $current->children[$segment] = new RouteNode();
                }

                $current = $current->children[$segment];
            }
        }

        $current->route = $route;
    }

    private function isParam(string $segment): bool
    {
        return $segment[0] === '{' && str_ends_with($segment, '}');
    }
}