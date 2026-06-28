<?php

namespace Src\Response;

class Response
{
    private int $status = 200;
    private array $headers = [];
    private mixed $body = null;

    public function status(int $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function header(string $key, string $value): static
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function json(array $data, int $flags = JSON_UNESCAPED_UNICODE): static
    {
        $this->header('Content-Type', 'application/json; charset=utf-8');

        $encoded = json_encode($data, $flags);

        if ($encoded === false) {
            throw new \RuntimeException(json_last_error_msg());
        }

        $this->body = $encoded;

        return $this;
    }

    public function text(string $text): static
    {
        $this->header('Content-Type', 'text/plain; charset=utf-8');
        $this->body = $text;

        return $this;
    }

    public function html(string $html): static
    {
        $this->header('Content-Type', 'text/html; charset=utf-8');
        $this->body = $html;

        return $this;
    }

    public function redirect(string $url, int $code = 302): static
    {
        $this->status = $code;
        $this->header('Location', $url);

        return $this;
    }

    public function send(): void
    {
        if (!headers_sent()) {
            http_response_code($this->status);

            foreach ($this->headers as $key => $value) {
                header("{$key}: {$value}");
            }
        }

        echo $this->body;
    }
}