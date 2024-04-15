<?php

class Router {
    protected $routes = [];
    protected $routesID = [];
    protected $middleware = [];
    public function addRoute($method, $path, $callback) {
        if (substr($path, -1) !== "/") {
            $path .= "/";
        }
        $this->routes[$method][$path] = $callback;
        return $this;
    }

    public function addMiddleware($method, $path, $middleware) {
        if (substr($path, -1) !== "/") {
            $path .= "/";
        }
        // $this->middleware[$method][$path] = new $middleware;
        if(!isset($this->middleware[$method][$path])) { 
            $this->middleware[$method][$path] = [new $middleware];
        }
        else {
            array_push($this->middleware[$method][$path], new $middleware);
        }
        return $this;
    }

    public function handle($method, $path) {
        require_once './controllers/Controller.php';
        $routeFound = false;
        $id = null;
        if (substr($path, -1) !== "/") {
            $path .= "/";
        }
        // CEK APAKAH PATH EXIST DI PROPERTY ROUTES DENGAN METHOD YANG DIGUNAKAN
        if (isset($this->routes[$method][$path])) {
            $routeFound = true;
        }
        // KALO TIDAK
        else {
            // CEK APAKAH PATH ITU INDEX PAGE ATAU BUKAN JIKA IYA MAKA AKAN KEMBALIKAN HALAMAN 404
            if($path != "/" && trim($path) != "") {
                $pathExplode = array_values(array_filter(explode('/', $path)));
                $id = end($pathExplode);
                $path = '/' . implode('/', array_slice($pathExplode, 0, count($pathExplode) - 1));
                unset($this->routes[$method][$path]); // UNSET PATH YANG TIDAK PAKE ID
                $ALLROUTES = array_keys($this->routes[$method]);
                foreach($ALLROUTES as $route) {
                    if(strpos($route, $path . '/:') === 0) {
                        $routeFound = true;
                        $parameter = array_values(array_filter(explode('/:', $route)));
                        $parameter = end($parameter);
                        $path = $path . '/:' . $parameter;
                    }
                }
            }
        }

        if($routeFound) {
            $callback = $this->routes[$method][$path];
            if (is_callable($callback)) {
                if(isset($this->middleware[$method][$path])) {
                    $middlewares = $this->middleware[$method][$path];
                    // CEK MIDDLEWARE
                    $next = function() use(&$middlewares, $callback, &$next) {
                        if(empty($middlewares)) {
                            call_user_func($callback);
                        }
                        $middleware = array_shift($middlewares);
                        if(!empty($middleware)) {
                            return $middleware->handle($next);
                        }
                    };
                    $next();
                    return true;
                }
                call_user_func($callback);
                return true;
            } elseif (is_string($callback)) {
                $parts = explode('@', $callback);
                $controllerName = $parts[0];
                $fileName = $controllerName;
                if(strpos($controllerName,'/') !== false) {
                    $controllerName = array_values(array_filter(explode('/', $controllerName)));
                    $controllerName = end($controllerName);
                }
                $methodName = $parts[1];
                require_once('./controllers/' . $fileName . '.php');
                $controller = new $controllerName();
                if(isset($this->middleware[$method][$path])) {
                    $middlewares = $this->middleware[$method][$path];
                    // CEK MIDDLEWARE
                    $next = function() use(&$middlewares, $controller, $methodName, $id, &$next) {
                        if(empty($middlewares)) {
                            call_user_func([$controller, $methodName], $id);
                        }
                        $middleware = array_shift($middlewares);
                        if(!empty($middleware)) {
                            return $middleware->handle($next);
                        }
                    };
                    $next();
                    return true;
                }
                call_user_func([$controller, $methodName], $id);
                return true;
            }
        }
        else {
            echo "<h1>404 - Route not found!</h1>";
        }
        return false;
    }
}