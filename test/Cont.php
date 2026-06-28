<?php
namespace Test;

use Src\Response\Response;

class Cont{
    public function __invoke()
    {
        return response()->json([
            "my"=>"noga",
            "your"=>"Germainio"
        ]);
    }
}