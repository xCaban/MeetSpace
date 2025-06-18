<?php

class Routing {
    private static array $routes = [];

    public static function get(string $url, string $controllerMethod) {
        self::$routes['GET:' . $url] = $controllerMethod;
    }

    public static function post(string $url, string $controllerMethod) {
        self::$routes['POST:' . $url] = $controllerMethod;
    }

    public static function delete(string $url, string $controllerMethod) {
        self::$routes['DELETE:' . $url] = $controllerMethod;
    }

    public static function put(string $url, string $controllerMethod) {
        self::$routes['PUT:' . $url] = $controllerMethod;
    }

    public static function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = trim($url, '/');

        if ($url === '') {
            if ($method === 'GET') {
                $controller = new DefaultController();
                $controller->index();
                return;
            } else {
                http_response_code(405);
                echo "Method not allowed";
                return;
            }
        }

        $routeKey = $method . ':' . $url;

        if (isset(self::$routes[$routeKey])) {
            $controllerMethod = self::$routes[$routeKey];
            [$controllerName, $methodName] = explode('@', $controllerMethod);

            if (!class_exists($controllerName)) {
                http_response_code(500);
                echo "Controller $controllerName does not exist";
                return;
            }

            $controller = new $controllerName();

            if (!method_exists($controller, $methodName)) {
                http_response_code(500);
                echo "Method $methodName does not exist in controller $controllerName";
                return;
            }

            $controller->$methodName();
            return;
        }

        foreach (self::$routes as $route => $controllerMethod) {
            [$routeMethod, $routePath] = explode(':', $route, 2);

            if ($routeMethod !== $method)
                continue;

            $routeParts = explode('/', $routePath);
            $urlParts = explode('/', $url);

            if (count($routeParts) === count($urlParts)) {
                $params = [];
                $matched = true;
                for ($i = 0; $i < count($routeParts); $i++) {
                    if (preg_match('/^{\w+}$/', $routeParts[$i])) {
                        $params[] = $urlParts[$i];
                    } else if ($routeParts[$i] !== $urlParts[$i]) {
                        $matched = false;
                        break;
                    }
                }

                if ($matched) {
                    [$controllerName, $methodName] = explode('@', $controllerMethod);

                    if (!class_exists($controllerName)) {
                        http_response_code(500);
                        echo "Controller $controllerName does not exist";
                        return;
                    }

                    $controller = new $controllerName();

                    if (!method_exists($controller, $methodName)) {
                        http_response_code(500);
                        echo "Method $methodName does not exist in controller $controllerName";
                        return;
                    }

                    call_user_func_array([$controller, $methodName], $params);
                    return;
                }
            }
        }

        http_response_code(404);
        echo "404 Not Found - route not defined for $method $url";
    }
} 