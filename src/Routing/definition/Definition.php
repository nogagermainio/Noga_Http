<?php
namespace Src\Routing\Definition;


class Definition{
    public function __construct(
        public DefinitionType $type,
        public array|string|null $execute = null,
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

