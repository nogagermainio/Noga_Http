<?php
namespace Src\Exceptions;

class UnauthorizedHttpException extends HttpException{
  
    public function __construct(string $message = "Unauthorized")
    {
         parent::__construct($message);
    }
    
}