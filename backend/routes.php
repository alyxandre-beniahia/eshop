<?php
class Router
{
    private $routes = [];

    public function define($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function handle($method, $path)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                $handler = $route['handler'];
                return call_user_func($handler);
            }
        }

        // Handle 404 Not Found
        http_response_code(404);
        echo "Route not found";
    }
}

$router = new Router();

// Products routes
$router->define('GET', '/products', function() {
    ob_start();
    include_once 'routes/productRoutes.php';
    $output = ob_get_clean();

    $json_start = strpos($output, '{');
    if ($json_start !== false) {
        $json_response = json_decode(substr($output, $json_start), true);
        if ($json_response) {
            echo json_encode($json_response);
        } else {
            echo json_encode(array("message" => "Invalid JSON response"));
        }
    } else {
        echo json_encode(array("message" => "No JSON response found"));
    }
});

$router->define('POST', '/products', function() {
    include_once 'routes/productRoutes.php';
});

$router->define('PUT', '/products', function() {
    include_once 'routes/productRoutes.php';
});

$router->define('DELETE', '/products', function() {
    include_once 'routes/productRoutes.php';
});

$router->define('GET', '/discounts', function() {
    ob_start();
    include_once 'routes/discountRoutes.php';
    $output = ob_get_clean();
    $json_start = strpos($output, '{');
    if ($json_start !== false) {
        $json_response = json_decode(substr($output, $json_start), true);
        if ($json_response) {
            echo json_encode($json_response);
        } else {
            echo json_encode(array("message" => "Invalid JSON response"));
        }
    } else {
        echo json_encode(array("message" => "No JSON response found"));
    }
});

$router->define('POST', '/discounts', function() {
    include_once 'routes/discountRoutes.php';
});

$router->define('PUT', '/discounts', function() {
    include_once 'routes/discountRoutes.php';
});

$router->define('DELETE', '/discounts', function() {
    include_once 'routes/discountRoutes.php';
});

$router->define('GET', '/order_details', function() {
    include_once 'routes/OrderDetailsRoutes.php';
});

$router->define('POST', '/order_details', function() {
    include_once 'routes/OrderDetailsRoutes.php';
});

$router->define('PUT', '/order_details', function() {
    include_once 'routes/OrderDetailsRoutes.php';
});

$router->define('DELETE', '/order_details', function() {
    include_once 'routes/OrderDetailsRoutes.php';
});

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->handle($method, $path);

?>