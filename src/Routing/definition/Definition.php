<?php
namespace Src\Routes\Definition;


class Definition{
    public function __construct(
        public DefinitionType $type,
        public array $execute = [],
        public mixed $runtime = null
    ){}

    public function toArray():array{
        $r = ($this->runtime !== null) ? true : false; 
        return [
            "type"=>$this->type,
            "execute"=>$this->execute,
            "runtime"=>$r
        ];
    }
}

