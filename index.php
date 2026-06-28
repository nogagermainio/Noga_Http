<?php

require "vendor/autoload.php";


use Src\Response\Response;
use Src\Routes;

request()->cors();

Routes::config(
    "Test",
    "Test"
);

Response::getInstance(
    "layout/layout.php",
    "view/"
);

require "test/web.php";

Routes::cache();
$dispatch = Routes::dispatch();
if($dispatch instanceof Response){
    $dispatch->run();
}else{
    $dispatch;
}