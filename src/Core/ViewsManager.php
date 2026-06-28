<?php
namespace Src\Core;

class ViewsManager {

    private string $path;
    private ?string $views = null;

    public function __construct() {
        $this->path = __DIR__ . '/../../App/Views/' . $this->views . '.ng.php';
        $this->parseFile();
    }

public function render(?string $views): string {
     $this->views = $views;
     return $this->path;
}

private function parseFile():static{
     if ($this->views === null) {
        throw new \InvalidArgumentException("No view provided");
    }
  
    $phpPath = __DIR__ . '/../../App/Views/' . $this->views . '.php';

    if (file_exists($phpPath)) {
        $this->path = $phpPath;
    } else {
        throw new \RuntimeException("View file not found: {$this->views} ");
    }

    return $this;
}

}
