<?php
namespace Src\Routing\Matcher;

use Src\CacheManager\CacheManager;

class Matcher{
    private string $regexRouter = "#\{(\w+)(?::([^}]+))?\}#";
    private string $defaultRegexParams = "([^/]+)";
    private array $router = [];
    private array $keys = [];
    private string $keyData = "";

    public function __construct(array $router)
    {
        $this->router = $router;
        $this->keyData = $this->hash(
            $this->router['PATH'].(implode(
                ',',
                $this->router['WHERE']) ?? "")
            );
    }

    public static function handle(array $router):array{
        $instance = new static($router);

        $cache = $instance->loadCache($instance->keyData) ?? [];

       return (isset($cache) && !empty($cache)) ?
              $cache :
              $instance->splitUri();
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
        $data[$this->keyData] = ['pattern'=>"#^$pattern$#",'keys'=>$this->keys] ?? [];

        $this->addCache($data);

        return ['pattern'=>"#^$pattern$#",'keys'=>$this->keys[1] ?? []];
    }


    // public function match(array $router,string $uri):array{

    //     if(!preg_match($router['PATTERN'],$uri,$m)){
    //         return [false,[]];
    //     }

    //     \array_shift($m);

      
    //     $this->keys = $router['KEYS'];

    //     $m = \array_slice(
    //         $m,
    //         0,
    //         count($this->keys)
    //     );

    //     if(count($this->keys) !== count($m)){
    //         return [false,[]];
    //     }

    //     $this->params = $this->keys ? \array_combine($this->keys,$m) : [];

    //     return [true,$this->params];

    // }

    private function cache():CacheManager{
       return CacheManager::key("matcher")
        ->dir("match");
    }

    private function addCache(array $data):void{
            $this->cache()
             ->signature($data)
             ->data($data)
             ->put();    
    }

    private function loadCache(string $key):array{
        $data = $this->cache()->get() ?? [];
        return isset($data[$key]) ? $data[$key] : [];
    }

    private function hash(string $key):string{
       return \hash('xxh128',$key); 
    }


}