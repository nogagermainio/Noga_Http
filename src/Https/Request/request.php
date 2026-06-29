<?php
namespace Src\Https\Request;

class Request
{
    private string $method;
    private string $uri;
    private string $basePath;

    private array $query = [];
    private array $body  = [];
    private array $headers = [];

    private array $routeParams = [];

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
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->basePath = $basePath;

        $this->uri = $this->resolveUri();
        $this->query = $_GET ?? [];
        $this->body = $this->resolveBody();
    }

    private function resolveUri(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

        $uri = rtrim($uri, '/');

        if ($this->basePath !== '') {
            $uri = substr($uri, \strlen($this->basePath)) ?: '/';
        }

        return $uri;
    }

    private function resolveBody(): array
    {
        if (\in_array($this->method, ['POST','PUT','DELETE'])) {

            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

            if (str_contains($contentType, 'application/json')) {
                return json_decode(file_get_contents('php://input'), true) ?? [];
            }

            if (str_contains($contentType, 'application/x-www-form-urlencoded')) {
                parse_str(file_get_contents('php://input'), $data);
                return $data;
            }

            return $_POST ?? [];
        }

        return [];
    }

    public function method(): string
    {
        return $this->method;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function query(): array
    {
        return $this->query;
    }

    public function body(): array
    {
        return $this->body;
    }

    public function all(): array
    {
        return array_merge($this->query, $this->body, $this->routeParams);
    }

    public function setRouteParams(array $params): void
    {
        $this->routeParams = $params;
    }

    public function ip(): string
    {
        foreach ($this->keyIp as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = explode(',', $_SERVER[$key])[0];
                return filter_var(trim($ip), FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
            }
        }

        return '0.0.0.0';
    }
}