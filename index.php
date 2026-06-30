<?php
require "vendor/autoload.php";

use Src\Https\Response\Response;
use Src\Routes;

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