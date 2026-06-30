<?php
namespace Test;

class Controller{
    public static function index(){
        return \response()
        ->json([request()->ip()]);
    }

    public static function home(int $id){
        return \response()
        ->json(["id"=>$id]);
    }
}