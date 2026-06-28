<?php
namespace Src\Exception;

use InvalidArgumentException;
use Src\Response\Response;
use Throwable;

class ExceptionHttpHandle{
    public function handle(Throwable $e){
        return match (true){
            $e instanceof HttpException => $this->http($e),
            $e instanceof InvalidArgumentException => $this->error(400,$e->getMessage()),
            default => $this->error(500,$e->getMessage())
        };
    }

    private function http(HttpException $e):Response{
        return \response($e->status())
            ->json([
                "error"=>true,
                "code"=>$e->status(),
                "message"=>$e->getMessage(),
                "debug"=>[
                    "trace"=>\debug_backtrace()
                ] ?? null
            ]);
            
    }

    private function error(int $code,string $message){
        return \response($code)
            ->json([
                "error"=>true,
                "code"=>$code,
                "message"=>$message,
                "debug"=>  [
                    "trace"=>\debug_backtrace()
                ] ?? null
            ]);
    }
}