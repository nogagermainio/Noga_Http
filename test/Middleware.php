<?php
namespace Test;
use Closure;
use Src\Exceptions\MiddlewareHttpException;

class Middleware{
    public function index($params,$data,Closure $next){
        $b = true;
        if(!$b){
         throw new MiddlewareHttpException("Access Not Allowed !");
        }
        
        return $next();

    }

    public function home($params,$data,Closure $next){
        $b = true;
        if(!$b){
         throw new MiddlewareHttpException("Access Not Allowed !");
        }
        
        return $next();

    }
}