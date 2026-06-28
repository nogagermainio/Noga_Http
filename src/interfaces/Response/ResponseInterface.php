<?php
namespace Src\Interfaces\Response;

interface ResponseInterface
{

    /**
     * Summary of getInstance
     * @param mixed $layoutPath
     * @param mixed $viewPath
     * @return static
     */
    public static function getInstance(?string $layoutPath = null, ?string $viewPath = null): ?static;

    /**
     * Summary of status
     * @param int $status
     * @return \Src\Response\Response
     */
    public function status(int $status = 200) : \Src\Response\Response;

    /**
     * Summary of json
     * @param array $data
     * @param int $flags
     * @throws \RuntimeException
     * @return static
     */
    public function json(array $data, int $flags = JSON_UNESCAPED_UNICODE): static;

    /**
     * Summary of text
     * @param string|array $data
     * @return static
     */
    public function text(string | array $data): static;

    /**
     * Summary of context
     * @param array $context
     * @return static
     */
    public function context(array $context): static;

    /**
     * Summary of view
     * @param string $view
     * @return static
     */
    public function view(string $view): static;

    /**
     * Summary of redirect
     * @param string $url
     * @param int $code
     * @return static
     */
    public function redirect(string $url, int $code = 302): static;

    /**
     * Summary of run
     * @return void
     */
    public function run(): void;

}
