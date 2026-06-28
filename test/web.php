<?php

use Src\Routes;
use Test\Cont;
use Test\Controller;
use Test\Middleware;
use Test\NewCont;

function noga(){
return response()->json(['noga']);
}

Routes::get('/')
->controller('Controller.index')
->middleware([Middleware::class,"index"])
->middleware([Middleware::class,"home"])
->name('ng');

Routes::get('/x')
->controller('noga')
->name('agato');

Routes::get('/a')
->controller(function(){
    echo "Noga";
})
->name('ngsa');

Routes::get('/{id}')
->controller([Controller::class,"home"])
->where(["id"=>'\d+'])
->name('ngsc');

Routes::get('/cont')
->controller([Cont::class])
->name('ngsh');

Routes::get('/conts')
->controller(Cont::class)
->name('ngsf');

Routes::get('/con')
->controller(NewCont::class)
->name('gale');