<?php 
namespace Src\Interfaces\Request;
/**
 * Summary of RequestInterface
 */
interface RequestInterface{

    /**
     * Summary of add_name
     * @param mixed $name
     * @param mixed $route
     * @return static
     */
    public function add_name(?string $name, ?array $route):static;

    /**
     * Summary of name
     * @return ?string
     */
    public function name(): ?string;

    /**
     * Summary of route
     * @param mixed $route_name
     * @return string | array | null
     */
    public function route(?string $route_name = null): string | array | null;

    /**
     * Summary of method
     * @return string
     */
    public function method(): string;

     /**
     * Summary of getInstance
     * @param mixed $base_path
     * @return \Src\Request\Request
     */
    public static function getInstance(?string $base_path = ""):\Src\Request\Request;

    /**
     * Summary of uri
     * @return string
     */
    public function uri(): string;

    /**
     * Summary of getUserIP
     * @return string
     */
    public static function getUserIP(): string;

    /**
     * Summary of cors
     * @param string $origin
     * @param string $headers
     * @param array|string|null $method
     * @return void
     */
    public function cors(string $origin = "*",string $headers = "Content-Type,Authorization",array|string|null $method = null):void;

    /**
     * Summary of all
     * @return array
     */
    public function all(): array;



}