<?php
return array (
  'delay' => NULL,
  'signature' => '0f7a4cc7c2507a694fea71f047663ae2',
  'data' => 
  array (
    'GET' => 
    array (
      '/' => 
      array (
        'METHOD' => 'GET',
        'NAME' => 'ng',
        'PATH' => '/',
        'CONTROLLER' => 
        array (
          'type' => 
          \Src\Routing\Definition\DefinitionType::ClassMethod,
          'execute' => 
          array (
            0 => 'Test\\Controller',
            1 => 'index',
          ),
          'runtime' => false,
        ),
        'MIDDLEWARES' => 
        array (
          0 => 
          array (
            0 => 'Test\\Middleware',
            1 => 'index',
          ),
          1 => 
          array (
            0 => 'Test\\Middleware',
            1 => 'home',
          ),
        ),
        'WHERE' => 
        array (
        ),
        'PATTERN' => '#^/$#',
        'KEYS' => 
        array (
        ),
      ),
      '/x' => 
      array (
        'METHOD' => 'GET',
        'NAME' => 'agato',
        'PATH' => '/x',
        'CONTROLLER' => 
        array (
          'type' => 
          \Src\Routing\Definition\DefinitionType::Function,
          'execute' => 'noga',
          'runtime' => false,
        ),
        'MIDDLEWARES' => 
        array (
        ),
        'WHERE' => 
        array (
        ),
        'PATTERN' => '#^/x$#',
        'KEYS' => 
        array (
        ),
      ),
      '/a' => 
      array (
        'METHOD' => 'GET',
        'NAME' => 'ngsa',
        'PATH' => '/a',
        'CONTROLLER' => 
        array (
          'type' => 
          \Src\Routing\Definition\DefinitionType::Closure,
          'execute' => NULL,
          'runtime' => true,
        ),
        'MIDDLEWARES' => 
        array (
        ),
        'WHERE' => 
        array (
        ),
        'PATTERN' => '#^/a$#',
        'KEYS' => 
        array (
        ),
      ),
      '/{id}' => 
      array (
        'METHOD' => 'GET',
        'NAME' => 'ngsc',
        'PATH' => '/{id}',
        'CONTROLLER' => 
        array (
          'type' => 
          \Src\Routing\Definition\DefinitionType::ClassMethod,
          'execute' => 
          array (
            0 => 'Test\\Controller',
            1 => 'home',
          ),
          'runtime' => false,
        ),
        'MIDDLEWARES' => 
        array (
        ),
        'WHERE' => 
        array (
          'id' => '\\d+',
        ),
        'PATTERN' => '#^/(\\d+)$#',
        'KEYS' => 
        array (
          0 => 'id',
        ),
      ),
      '/cont' => 
      array (
        'METHOD' => 'GET',
        'NAME' => 'ngsh',
        'PATH' => '/cont',
        'CONTROLLER' => 
        array (
          'type' => 
          \Src\Routing\Definition\DefinitionType::Invokable,
          'execute' => 
          array (
            0 => 'Test\\Cont',
          ),
          'runtime' => false,
        ),
        'MIDDLEWARES' => 
        array (
        ),
        'WHERE' => 
        array (
        ),
        'PATTERN' => '#^/cont$#',
        'KEYS' => 
        array (
        ),
      ),
      '/conts' => 
      array (
        'METHOD' => 'GET',
        'NAME' => 'ngsf',
        'PATH' => '/conts',
        'CONTROLLER' => 
        array (
          'type' => 
          \Src\Routing\Definition\DefinitionType::Invokable,
          'execute' => 
          array (
            0 => 'Test\\Cont',
          ),
          'runtime' => false,
        ),
        'MIDDLEWARES' => 
        array (
        ),
        'WHERE' => 
        array (
        ),
        'PATTERN' => '#^/conts$#',
        'KEYS' => 
        array (
        ),
      ),
    ),
  ),
);