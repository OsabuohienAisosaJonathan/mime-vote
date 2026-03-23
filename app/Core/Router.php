<?php
namespace app\Core;

class Router {
    private $routes = [];

    public function get($route, $action) {
        $this->addRoute('GET', $route, $action);
    }

    public function post($route, $action) {
        $this->addRoute('POST', $route, $action);
    }

    private function addRoute($method, $route, $action) {
        // Convert route to regex
        $route = preg_replace('/\//', '\/', $route);
        $route = '/^' . $route . '$/';
        $this->routes[$method][$route] = $action;
    }

    public function dispatch($uri, $method) {
        // Extract route from URI
        $urlParams = explode('?', $uri);
        $uriString = str_replace('/MIME-VOTE', '', $urlParams[0]);
        if (empty($uriString)) {
            $uriString = '/';
        }

        if (array_key_exists($method, $this->routes)) {
            foreach ($this->routes[$method] as $route => $action) {
                if (preg_match($route, $uriString, $matches)) {
                    array_shift($matches); // Remove the full match

                    list($controller, $methodName) = explode('@', $action);
                    $controllerPath = APP_DIR . '/Controllers/' . $controller . '.php';

                    if (file_exists($controllerPath)) {
                        require_once $controllerPath;
                        $controllerClass = "app\\Controllers\\" . $controller;
                        $controllerObject = new $controllerClass();
                        
                        // Call the method and pass captured regex params
                        call_user_func_array([$controllerObject, $methodName], $matches);
                        return;
                    }
                }
            }
        }

        // 404 Not Found
        http_response_code(404);
        echo "<h1 style='text-align:center; padding: 50px; font-family: sans-serif;'>404 - Page Not Found</h1>";
    }
}
