<?php
class Router
{
    private $routes = [];

    public function add($route, $file)
    {
        $this->routes[$route] = $file;
    }

    public function route($url)
    {
        // Remove query string
        $url = strtok($url, '?');
        $url = trim($url, '/');

        // Debug: Log the received route
        error_log("Router received URL: '$url'");
        error_log("Available routes: " . implode(', ', array_keys($this->routes)));

        // Default to empty string for home if empty (to match route definition)
        if (empty($url)) {
            $url = '';
        }

        // Check if route exists
        if (array_key_exists($url, $this->routes)) {
            $handler = $this->routes[$url];
            
            // Check if handler is a closure/function
            if (is_callable($handler)) {
                error_log("Found route '$url', executing handler function");
                call_user_func($handler);
            } 
            // Check if handler is a file path
            else if (is_string($handler)) {
                error_log("Found route '$url', loading file: $handler");
                if (file_exists($handler)) {
                    include $handler;
                } else {
                    error_log("File not found: $handler");
                    $this->show404();
                }
            } else {
                error_log("Invalid handler type for route: $url");
                $this->show404();
            }
        } else {
            error_log("Route '$url' not found");
            $this->show404();
        }
    }

    private function show404()
    {
        http_response_code(404);
        include __DIR__ . '/404.php';
    }
}
