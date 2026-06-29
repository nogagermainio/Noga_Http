<?php
namespace Src\Routing\Definition;

class DefinitionMatcher{
    public function __construct(
        public string $pattern,
        public array $keys = []
    )
    {}

    public function toArray():array{
        return [
            "pattern"=>$this->pattern,
            "keys"=>$this->keys
        ];
    }
}