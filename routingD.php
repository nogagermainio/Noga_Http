<?php

namespace Src\Routes\Tree;

use Src\Routes\Definition\Definition;

class RouteNode
{
    public array $children = [];

    public ?array $route = null;

    public bool $isParam = false;
    public ?string $paramName = null;

    public ?string $regex = null;

    public function getStatic(string $segment): ?self
    {
        return $this->children[$segment] ?? null;
    }

    public function getParam(): ?self
    {
        foreach ($this->children as $child) {
            if ($child->isParam) {
                return $child;
            }
        }

        return null;
    }
}