<?php
namespace Src\Exceptions;

class MiddlewareHttpException extends HttpException{
    public function __construct(string $message = "Middleware have problem")
    {
        parent::__construct(400, $message);
    }
}