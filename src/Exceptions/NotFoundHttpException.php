<?php
namespace Src\Exceptions;

class NotFoundHttpException extends HttpException{
    public function __construct(string $message = "Not Found")
    {
        parent::__construct(404,$message);
    }
}