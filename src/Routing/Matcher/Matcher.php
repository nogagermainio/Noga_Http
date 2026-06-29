<?php
namespace Src\Routing\Matcher;

use Src\CacheManager\CacheManager;

class Matcher{
    private string $regexRouter = "#\{(\w+)(?::([^}]+))?\}#";
    private string $defaultRegexParams = "([^/]+)";
    private array $router = [];
    private string $uri = "";
    private array $params = [];
    private array $keys = [];
    private ?CacheManager $cacheManager = null;

    public function __construct(array $router,string $uri)
    {
        $this->router = $router;
        $this->uri = $uri;
    }


    public function splitUri():array{
        \preg_match_all(
            $this->regexRouter,
            $this->router['PATH'],
            $this->keys
        );

        $pattern = \preg_replace_callback(
            $this->regexRouter,
            function($m){
                $params = $m[1];

                if(isset($this->router["WHERE"][$params])){
                    return "({$this->router['WHERE'][$params]})";
                }

                return isset($m[2]) ? "({$m[2]})" : $this->defaultRegexParams;
            },
            $this->router['PATH']
        );

        return[
            'pattern'=>"#^$pattern$#",
            "keys"=>$this->keys
        ];
    }


    public function match():array{
        if(!preg_match($this->router['PATTERN'],$this->uri,$m)){
            return [false,[]];
        }

        \array_shift($m);

      
        $this->keys = $this->router['KEYS'];

        $m = \array_slice(
            $m,
            0,
            count($this->keys)
        );

        if(count($this->keys) !== count($m)){
            return [false,[]];
        }

        $this->params = $this->keys ? \array_combine($this->keys,$m) : [];

        return [true,$this->params];

    }

    private function cache():CacheManager{
       return $this->cacheManager = CacheManager::key("matcher")
        ->dir("match");
    }


}