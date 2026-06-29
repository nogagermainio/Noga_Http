<?php
namespace Src\Exception;

class ClassNotFoundException extends HttpException{
    public function __construct(string $message = "Class Not Found !")
    {
        parent::__construct(405,$message);
    }
}