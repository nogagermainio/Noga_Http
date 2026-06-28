<?php
namespace Src\Exception;

class ControllerHttpException extends HttpException{
    public function __construct(string $message = 'Controller not Allowed'){
        parent::__construct(405, $message);
    }
}