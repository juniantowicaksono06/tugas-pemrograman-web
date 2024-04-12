<?php

class Router {
    protected $routes = [];
    public function addRoute($method, $path, $callback) {
        $this->routes[$method][$path] = $callback;
    }

    public function handle($method, $path) {
        require_once './controllers/Controller.php';
        // CEK APAKAH METHOD CONTROLLER SAMA DENGAN METHOD YANG DIGUNAKAN
        $parameter = explode('/', $path);
        $path = '/' . $parameter[1];  
        $parameter = count($parameter) > 0 ? end($parameter) : null;
        if (isset($this->routes[$method][$path])) {
            $callback = $this->routes[$method][$path];
            if (is_callable($callback)) {
                call_user_func($callback);
            } elseif (is_string($callback)) {
                $parts = explode('@', $callback);
                $controllerName = $parts[0];
                $methodName = $parts[1];
                require_once('./controllers/' . $controllerName . '.php');
                $controller = new $controllerName();
                call_user_func([$controller, $methodName], $parameter);
            }
        } else {
            // Handle 404 Not Found
            echo "404 Not Found";
        }
    }
}