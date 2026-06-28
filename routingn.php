<?php

namespace Src\Routes\Tree;

class RouteMatcher
{
    public function match(RouteNode $root, string $uri): ?array
    {
        $segments = array_values(array_filter(explode('/', trim($uri, '/'))));

        $current = $root;
        $params = [];

        $i = 0;
        $len = count($segments);

        while ($i < $len) {

            $segment = $segments[$i];

            // 1. STATIC MATCH O(1)
            $next = $current->getStatic($segment);

            if ($next) {
                $current = $next;
                $i++;
                continue;
            }

            // 2. PARAM MATCH
            $paramNode = $current->getParam();

            if ($paramNode) {

                if ($paramNode->regex && !preg_match('#^' . $paramNode->regex . '$#', $segment)) {
                    return null;
                }

                $params[$paramNode->paramName] = $segment;

                $current = $paramNode;
                $i++;

                continue;
            }

            return null;
        }

        return $current->route ? [
            'route' => $current->route,
            'params' => $params
        ] : null;
    }
}