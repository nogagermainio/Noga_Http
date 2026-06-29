<?php 
namespace Src\Exception;

class MethodNotAllowedHttpException extends HttpException{

    public function __construct(string $message = "Method Not Allowed")
    {
        parent::__construct(405, $message);
    }
}