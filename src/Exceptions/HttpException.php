<?php

namespace Src\Exceptions;

use Exception;
use Throwable;

class HttpException extends Exception{
    public function __construct(
        protected int $statusCode, 
        string $message = "", 
        Throwable|null $previous = null
        )
    {
        parent::__construct(
            $message,
             $statusCode, 
             $previous
            );
    }

    public function status():int{
        return $this->code;
    }

}
