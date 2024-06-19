<?php
namespace Router;
class Router {
    protected $routes = [];
    protected $routesID = [];
    protected $middleware = [];
    protected $groupMiddleware = [];

    public function addRoute($method, $path, $callback) {
        if (substr($path, -1) !== "/") {
            $path .= "/";
        }
        $this->routes[$method][$path] = $callback;
        if (!empty($this->groupMiddleware)) {
            foreach ($this->groupMiddleware as $middleware) {
                $this->addMiddleware($method, $path, $middleware);
            }
        }
        return $this;
    }

    public function addMiddleware($method, $path, $middleware) {
        if (substr($path, -1) !== "/") {
            $path .= "/";
        }
        $middleware = "Middleware\\" . $middleware;
        if (!isset($this->middleware[$method][$path])) { 
            $this->middleware[$method][$path] = [new $middleware];
        } else {
            array_push($this->middleware[$method][$path], new $middleware);
        }
        return $this;
    }

    public function group($middleware, $callback) {
        $this->groupMiddleware[] = $middleware;
        call_user_func($callback, $this);
        array_pop($this->groupMiddleware);
    }

    public function handle($method, $path) {
        // require_once './controllers/Controller.php';
        $routeFound = false;
        $id = null;
        if (substr($path, -1) !== "/") {
            $path .= "/";
        }
        if (isset($this->routes[$method][$path])) {
            $routeFound = true;
        } else {
            if ($path != "/" && trim($path) != "") {
                $pathExplode = array_values(array_filter(explode('/', $path)));
                $id = end($pathExplode);
                $id = explode('?', $id);
                $id = $id[0];
                $path = '/' . implode('/', array_slice($pathExplode, 0, count($pathExplode) - 1));
                unset($this->routes[$method][$path]);
                $ALLROUTES = array_keys($this->routes[$method]);
                foreach ($ALLROUTES as $route) {
                    if (strpos($route, $path . '/:') === 0) {
                        $routeFound = true;
                        $path = $route;
                    }
                }
            }
        }

        if ($routeFound) {
            $callback = $this->routes[$method][$path];
            if (is_callable($callback)) {
                if (isset($this->middleware[$method][$path])) {
                    $middlewares = $this->middleware[$method][$path];
                    $next = function() use (&$middlewares, $callback, &$next) {
                        if (empty($middlewares)) {
                            call_user_func($callback);
                        }
                        $middleware = array_shift($middlewares);
                        if (!empty($middleware)) {
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
                if (strpos($controllerName, '/') !== false) {
                    $controllerName = str_replace('/', '\\', $controllerName);
                }
                $controller = new $controllerName();
                $methodName = $parts[1];
                if (method_exists($controller, $methodName)) {
                    if (isset($this->middleware[$method][$path])) {
                        $middlewares = $this->middleware[$method][$path];
                        $next = function() use (&$middlewares, $controller, $methodName, &$next, $id) {
                            if (empty($middlewares)) {
                                call_user_func([$controller, $methodName], $id);
                            }
                            $middleware = array_shift($middlewares);
                            if (!empty($middleware)) {
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
        }

        // Handle 404 Not Found
        http_response_code(404);
        echo '404 Not Found';
        return false;
    }
}
