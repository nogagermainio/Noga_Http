<?php

use Src\Https\Request\Request;
use Src\Https\Response\Response;
use Src\Views\ViewsManager;

 /**
  * Summary of App\Util\clean
  * @param string $name valeur a nettoyer
  * @return string return string claire
  */
 function clean(string $name):string{
    // $name = strtolower($name);
    $name = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$name);
    $name = preg_replace('/[^\w]+/','_',$name);
    $name = preg_replace('/_+/','_',$name);
    $name = trim($name, '_');
    return $name;
}

function view(?string $views):string{
     $render = (new ViewsManager())->render($views);
     return $render ?? null;
}

/**
 * Summary of App\Util\post
 * @param string $postPath
 * @return Response
 */
function post(?string $postPath):Response
{
    $post = trim($postPath);
    $post = str_replace('/','\\',$post);
    $class = '\\App\\Repository'.$post;

    if (!class_exists($class)) {
        return response()
            ->status(404)
            ->json([
                'status' => false,
                'error' => $class
            ]);
    }

    $action = new $class();

    if (!method_exists($action, 'handle')) {
        return response()
            ->status(500)
            ->json([
                'status' => false,
                'error' => 'Méthode handle() manquante'
            ]);
    }

    return $action->handle($_POST);
}

function request():Request{
    return new Request();
}

function response(int $status = 200):Response{
   return Response::getInstance()->status($status);
}

/**
 * Summary of getParams
 * @param string $uri si avais de uri
 * @return array|string|null
 */
function getParams(?string $uri = ""):array|string|null{
    $paramsURL = [];
    
    $path = !empty($uri) ? $uri : $_SERVER['REQUEST_URI'];
    
    $path = trim($path,'/');
   //get basename
    $basename = basename($path);
    $dir = basename(dirname($path));

    $paramsURL['dirname'] = $dir;
    //regex
    $match = [
        '/\//',
        '/\&(\w+)?\=?(\w+)?/',
        '/\?(\w+)?\=?(\w+)?/'
    ];
    
   foreach($match as $regex){
    $basename = preg_replace($regex,'',$basename);
   }

    $paramsURL['basename'] = $basename;

    return $paramsURL;
}
