<?php
namespace Src\Routing\Builder;

class PathBuilder{

    public function __construct(
        public string $path,
        public array $groupStack = []
    )
    {}

    public function normalize(string $path):string{
         $paths = '';
        if (str_contains($path, '.')) {
            $paths = explode('.', trim($path, '/'));
            $paths = implode('/', $paths);

        } else {
            $paths = trim($this->path, '/');
        }

        return $paths === '/' ? '/' : "/$paths";
    }

    public function finalPath():string{
        $prefix = '';
        $paths  = '';
        foreach ($this->groupStack as $group) {
            $prefix .= $group['prefix'];
        }

        $paths = "$prefix/$this->path";

        return $this->normalize($paths);
    }


}