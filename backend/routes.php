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
        http_response_code(404);
        echo "Route not found";
    }
}

function handleJsonResponse($routeFile) {
    ob_start();
    include_once $routeFile;
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
}

$router = new Router();


// ________________ Products routes ________________
$router->define('GET', '/products', function() {
    handleJsonResponse('routes/productRoutes.php');
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


// ________________ Discounts routes ________________
$router->define('GET', '/discounts', function() {
    handleJsonResponse('routes/discountRoutes.php');
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


// ________________ Order_details routes ________________
$router->define('GET', '/order_details', function() {
    handleJsonResponse('routes/OrderDetailsRoutes.php');
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

// ________________ ProductCategory routes ________________
$router->define('GET', '/categories', function() {
    handleJsonResponse('routes/productCategoryRoutes.php');
});

$router->define('POST', '/categories', function() {
    include_once 'routes/productCategoryRoutes.php';
});

$router->define('PUT', '/categories', function() {
    include_once 'routes/productCategoryRoutes.php';
});

$router->define('DELETE', '/categories', function() {
    include_once 'routes/productCategoryRoutes.php';
});

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->handle($method, $path);
?>