<?php
namespace Src\Exception;

class ClassMethodUndefined extends HttpException{ 
    public function __construct(string $message)
    {
        parent::__construct(500,$message);
    }
}