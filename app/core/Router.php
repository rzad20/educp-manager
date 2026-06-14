<?php
declare(strict_types=1);

/** Router sederhana berbasis ?page=... (jangan ganti dengan router lain). */
class Router
{
    /** @var array<string, array{0:string,1:string}> */
    private array $routes = [];

    public function add(string $page, string $controller, string $method): void
    {
        $this->routes[$page] = [$controller, $method];
    }

    public function dispatch(string $page): void
    {
        if (!isset($this->routes[$page])) {
            http_response_code(404);
            require BASE_PATH . '/app/views/errors/404.php';
            return;
        }

        [$controller, $method] = $this->routes[$page];
        $file = BASE_PATH . '/app/controllers/' . $controller . '.php';

        if (!is_file($file)) {
            http_response_code(404);
            require BASE_PATH . '/app/views/errors/404.php';
            return;
        }

        require $file;

        if (!class_exists($controller) || !method_exists($controller, $method)) {
            http_response_code(404);
            require BASE_PATH . '/app/views/errors/404.php';
            return;
        }

        $instance = new $controller();
        $instance->{$method}();
    }
}
