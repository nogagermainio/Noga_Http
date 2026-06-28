<?php
return array (
  'delay' => NULL,
  'signature' => '0a822804ae76cc28a647cd6c3b0dbe22',
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
          \Src\Routes\Definition\DefinitionType::ClassMethod,
          'execute' => 
          array (
            0 => 'Test\\Controller',
            1 => 'index',
          ),
          'runtime' => false,
        ),
        'MIDDLEWARES' => 
        array (
          'type' => 
          \Src\Routes\Definition\DefinitionType::ClassMethod,
          'execute' => 
          array (
            0 => 'Test\\Middleware',
            1 => 'index',
          ),
          'runtime' => false,
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
          \Src\Routes\Definition\DefinitionType::Function,
          'execute' => 
          array (
            0 => 'noga',
          ),
          'runtime' => false,
        ),
        'MIDDLEWARES' => 
        array (
          'type' => 
          \Src\Routes\Definition\DefinitionType::Null,
          'execute' => 
          array (
          ),
          'runtime' => false,
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
          \Src\Routes\Definition\DefinitionType::Closure,
          'execute' => 
          array (
          ),
          'runtime' => true,
        ),
        'MIDDLEWARES' => 
        array (
          'type' => 
          \Src\Routes\Definition\DefinitionType::Null,
          'execute' => 
          array (
          ),
          'runtime' => false,
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
          \Src\Routes\Definition\DefinitionType::ClassMethod,
          'execute' => 
          array (
            0 => 'Test\\Controller',
            1 => 'home',
          ),
          'runtime' => false,
        ),
        'MIDDLEWARES' => 
        array (
          'type' => 
          \Src\Routes\Definition\DefinitionType::Null,
          'execute' => 
          array (
          ),
          'runtime' => false,
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
          \Src\Routes\Definition\DefinitionType::Invokable,
          'execute' => 
          array (
            0 => 'Test\\Cont',
          ),
          'runtime' => false,
        ),
        'MIDDLEWARES' => 
        array (
          'type' => 
          \Src\Routes\Definition\DefinitionType::Null,
          'execute' => 
          array (
          ),
          'runtime' => false,
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
          \Src\Routes\Definition\DefinitionType::Invokable,
          'execute' => 
          array (
            0 => 'Test\\Cont',
          ),
          'runtime' => false,
        ),
        'MIDDLEWARES' => 
        array (
          'type' => 
          \Src\Routes\Definition\DefinitionType::Null,
          'execute' => 
          array (
          ),
          'runtime' => false,
        ),
        'WHERE' => 
        array (
        ),
        'PATTERN' => '#^/conts$#',
        'KEYS' => 
        array (
        ),
      ),
      '/con' => 
      array (
        'METHOD' => 'GET',
        'NAME' => 'gale',
        'PATH' => '/con',
        'CONTROLLER' => 
        array (
          'type' => 
          \Src\Routes\Definition\DefinitionType::Classes,
          'execute' => 
          array (
            0 => 'Test\\NewCont',
          ),
          'runtime' => false,
        ),
        'MIDDLEWARES' => 
        array (
          'type' => 
          \Src\Routes\Definition\DefinitionType::Null,
          'execute' => 
          array (
          ),
          'runtime' => false,
        ),
        'WHERE' => 
        array (
        ),
        'PATTERN' => '#^/con$#',
        'KEYS' => 
        array (
        ),
      ),
    ),
  ),
);