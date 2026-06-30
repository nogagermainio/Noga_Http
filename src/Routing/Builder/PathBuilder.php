<?php
namespace Src\Routing\Builder;

class PathBuilder{

    public function __construct(
        public string $path,
        public array $groupStack = []
    )
    {}

    public function normalize():string{
         $paths = '';
        if (str_contains($this->path, '.')) {
            $paths = explode('.', trim($this->path, '/'));
            $paths = implode('/', $paths);

        } else {
            $paths = trim($this->path, '/');
        }

        return $paths === '/' ? '/' : "/$paths";
    }

    public function finalPath():string{
        $prefix = '';
        foreach ($this->groupStack as $group) {
            $prefix .= $group['prefix'];
        }

        $this->path = "$prefix/$this->path";

        return $this->normalize();
    }


}