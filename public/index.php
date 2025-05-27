<?php
require __DIR__ . '/../vendor/autoload.php';

// 1. Load routes
$routes = require __DIR__ . '/../config/routes.php';

// var_export($_SESSION);
// echo "<br />";

// 2. Initialize FastRoute
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) use ($routes) {
    foreach ($routes as $route) {
        // Standard route: [method, path, handler]
        if (count($route) === 3) {
            $r->addRoute($route[0], $route[1], $route[2]);
        }
        // Route with middleware: [method, path, handler, middleware]
        elseif (count($route) === 4) {
            $r->addRoute($route[0], $route[1], [
                'handler' => $route[2],
                'middleware' => $route[3]
            ]);
        }
    }
});

// 3. Handle current request
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 4. Dispatch the request
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

// 5. Route handling
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo "404 Not Found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo "405 Method Not Allowed";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];        
        // Handle middleware first
        if (is_array($handler) && isset($handler['middleware'])) {
            foreach ($handler['middleware'] as $middlewareClass) {
                $middleware = new $middlewareClass();
                if (!$middleware->handle()) {
                    return; // Stop if middleware fails
                }
            }
            $handler = $handler['handler']; // Get the actual handler
        }
        
        // Call the final handler
        if (is_callable($handler)) {
            echo $handler($vars);
        } elseif (is_array($handler)) {
            [$controllerClass, $method] = $handler;
            $controller = new $controllerClass();
            echo $controller->$method($vars);
        }
        break;
}