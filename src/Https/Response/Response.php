<?php declare(strict_types=1);
/**
 * Author : ZAFININGELY Noga Germainio
 * Mail : nogagerma122@gmail.com
 * Created at : Since february 2026
 * Title : Response http 
 */

namespace Src\Response;

use Src\Interfaces\Response\ResponseInterface;

class Response implements ResponseInterface
{
    private int $status            = 200;
    private array $headers         = [];
    private mixed $content         = null;
    private array $context         = [];
    private string $layout         = "";
    private string $viewPath       = "";
    private static ?self $instance = null;

    public function __construct(?string $layoutPath = null, ?string $viewPath = null)
    {
        $this->layout   = $layoutPath ?? __DIR__ . '/../../../App/View/layout/layout.php';
        $this->viewPath = $viewPath ?? __DIR__ . '/../../../App/View/';
    }

    /**
     * Summary of responseConfig
     * @param string $layoutPath
     * @param string $viewPath
     * @return static|null
     */
    public static function getInstance(?string $layoutPath = null, ?string $viewPath = null): ?static
    {
        if (self::$instance === null) {
            self::$instance = new static($layoutPath, $viewPath);
        }

        return self::$instance;
    }

    /**
     * Summary of status
     * @param int $status
     * @return Response
     */
    public function status(int $status = 200) : Response
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Summary of json
     * @param array $data
     * @param int $flags
     * @throws \RuntimeException
     * @return static
     */
    public function json(array $data, int $flags = JSON_UNESCAPED_UNICODE): static
    {
        $this->headers['Content-Type'] = 'application/json; charset=utf-8';

        $json = json_encode($data, $flags);
        if ($json === false) {
            throw new \RuntimeException(json_last_error_msg());
        }

        $this->content = $json;
        return $this;
    }

    /**
     * Summary of text
     * @param string|array $data
     * @return static
     */
    public function text(string | array $data): static
    {
        if (\is_array($data)) {
            $lines = [];
            foreach ($data as $k => $v) {
                $lines[] = "{$k}: {$v}";
            }
            $this->content = implode(PHP_EOL, $lines);

        } else {
            $this->content = $data;
        }

        return $this;
    }

    /**
     * Summary of context
     * @param array $context
     * @return static
     */
    public function context(array $context): static
    {
        $this->context = $context;
        return $this;
    }

    /**
     * Summary of view
     * @param string $view
     * @return static
     */
    public function view(string $view): static
    {
        ob_start();
        extract($this->context, \EXTR_SKIP);      // transforme tableau en variables
        require $this->viewPath . $view . '.php'; // exécution de la vue
        $content = ob_get_clean();                // stocke le HTML de la vue

        ob_start();            // capture le layout
        require $this->layout; // layout global
        $this->content = ob_get_clean();

        return $this;
    }

    /**
     * Summary of redirect
     * @param string $url
     * @param int $code
     * @return static
     */
    public function redirect(string $url, int $code = 302): static
    {
        $this->status              = $code;
        $this->headers['Location'] = $url;
        return $this;
    }

    /**
     * Summary of run
     * @return void
     */
    public function run(): void
    {
        if (! headers_sent()) {
            http_response_code($this->status);
            foreach ($this->headers as $k => $v) {
                header("$k: $v", true);
            }
        }
        echo $this->content;
        return;
    }
}
