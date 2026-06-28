<?php declare(strict_types=1);
/**
 * Author : ZAFININGELY Noga Germainio
 * Mail : nogagerma122@gmail.com
 * Created at : May 2026 
 * Title : Request Http
 * 
 */

namespace Src\Request;

use Src\Interfaces\Request\RequestInterface;

class Request implements RequestInterface
{
    private string $method = "";
    private string $uri = "";
    private string $basePath = "";
    private ?string $name          = null;
    protected ?array $route        = null;
    private static ?self $instance = null;
    private array $methodSupported = ["GET","POST","PUT","DELETE","OPTIONS"];
    private array $keyIp = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR',
    ];

    public function __construct(string $basePath = '')
    {
        $this->method   = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri      = '';
        $this->basePath = $basePath;
    }

    /**
     * Summary of add_name
     * @param mixed $name
     * @param mixed $route
     * @return static
     */
    public function add_name(?string $name, ?array $route):static
    {
        $this->name  = $name;
        $this->route[] = $route;

        return $this;
    }

    /**
     * Summary of name
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->name ?? null;
    }

    /**
     * Summary of route
     * @param mixed $route_name
     * @return string|array|null
     */
    public function route(?string $route_name = null): string | array | null
    {
            return ($route_name === null) ? 
            $this->route : 
            $this->route[$route_name];
    }

    // Récupérer la méthode HTTP
    public function method(): string
    {
        return strtoupper($this->method);
    }

    /**
     * Summary of getInstance
     * @param mixed $base_path
     * @return Request
     */
    public static function getInstance(?string $base_path = ""):Request
    {
           return static::$instance ??= new static($base_path);
    }

    /**
     * Summary of get_base_path
     * @return string
     */
    public function get_base_path(): string
    {
        return $this->basePath ?? '';
    }

    // Récupérer l'URI nettoyée
    public function uri(): string
    {
        if ($this->uri) {
            return $this->uri;
        }
        // cache

        $this->uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/', '');

        $this->uri = substr($this->uri, \strlen($this->basePath)) ?: '/';

        // Supprime paramètres GET inutiles
        $this->uri = preg_replace('/\&(\w+)?\=?(\w+)?/', '', $this->uri);

        // Redirection si slash final (mais ne supprime pas tous les '/')
        if ($this->uri !== '/' && str_ends_with($this->uri, '/')) {
            $this->uri = rtrim($this->uri, '/');
            header("Location: {$this->basePath}" . $this->uri, true, 301);
            return $this->uri;
        }

        return $this->uri;
    }

    // Récupérer l'IP de l'utilisateur
    public static function getUserIP(): string
    {    

        foreach (self::$instance->keyIp as $key) {
            if (! empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }

            }
        }

        return '0.0.0.0';
    }


       /**
       * Summary of cors
       * @param string $origin
       * @param string $headers
       * @param array|string|null $method
       * @return void
       */
      public function cors(string $origin = "*",string $headers = "Content-Type,Authorization",array|string|null $method = null):void{
        header("Access-Control-Allow-Origin: {$origin}");
        header("Access-Control-Allow-Headers: {$headers}"); //
        header("Access-Control-Allow-Methods: ".($method ??= \implode(',',$this->methodSupported)));
        
        if($this->method == "OPTIONS"){
            \http_response_code(200);
            return;
        }
    }

    // Récupérer tous les paramètres (GET, POST, PUT, DELETE)
    public function all(): array
    {
        $data = [];

        if (in_array($this->method(), ['POST', 'PUT', 'DELETE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

            if (str_contains($contentType, 'application/json')) {

                $json    = file_get_contents('php://input');

                $decoded = json_decode($json, true);

                if (\is_array($decoded)) {
                    $data = $decoded;
                }

            } elseif (str_contains($contentType, 'application/x-www-form-urlencoded')) {

                parse_str(file_get_contents('php://input'), $parsed);
                $data = $parsed;

            } else {
                $data = $_POST; // fallback
            }
        }

        if ($this->method() === 'GET') {
            $data = $_GET;
        }

        return $data;
    }

}
