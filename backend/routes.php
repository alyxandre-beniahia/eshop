<?php
include_once 'middleware/auth.php';
// Enable CORS
header("Access-Control-Allow-Origin: http://localhost:5173"); // Replace with your frontend origin
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200); // Send a successful HTTP status code
    exit;
}

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

$router = new Router();

$router->define('OPTIONS', '/*', function() {
    header("Access-Control-Allow-Origin: http://localhost:5173"); // Replace with your frontend origin
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");
    http_response_code(200);
    exit;
});

// Users routes Tested OK
// http://localhost:8000/users
$router->define('GET', '/users', function() {
    $secret_key = "secret";
    $userData = authenticateRequest($secret_key);
    if ($userData) {
        ob_start();
        include_once 'routes/userRoutes.php';
        $output = ob_get_clean();

        $json_response = json_decode($output, true);
        if ($json_response !== null) {
            echo json_encode($json_response);
        } else {
            echo json_encode(array("message" => "Invalid JSON response"));
        }
    }
});


$router->define('POST', '/users', function() {
    include_once 'routes/userRoutes.php';
});

$router->define('PUT', '/users', function() {
    $secret_key = "secret";
    $userData = authenticateRequest($secret_key);
    if ($userData) {
        include_once 'routes/userRoutes.php';
    }
});
$router->define('DELETE', '/users', function() {
    $secret_key = "secret";
    $userData = authenticateRequest($secret_key);
    if ($userData) {
        include_once 'routes/userRoutes.php';
    }
});

$router->define('POST', '/login', function() {
    include_once 'routes/userRoutes.php';
});

// Products routes tested OK
// http://localhost:8000/products
// object type to post to the server
//{
//    "name":"chemise rouge",
//    "brand":"Givenchy",
//    "description":"Chemise à manche longue rouge sang",
//    "price":500,
//    "discount_id":1,
//    "size_id":6,
//    "quantity":10,
//    "images":"https://images.pexels.com/photos/1103899/pexels-photo-1103899.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940"
// }
// to get the categories of a product http://localhost:8000/products?categories&id=4
//
//

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
    $secret_key = "secret";
    $userData = authenticateRequest($secret_key);
    if ($userData['user_role'] === 'admin') {
        include_once 'routes/productRoutes.php';
    }
});

$router->define('PUT', '/products', function() {
    $secret_key = "secret";
    $userData = authenticateRequest($secret_key);
    if ($userData['user_role'] === 'admin') {
        include_once 'routes/productRoutes.php';
    }
});

$router->define('DELETE', '/products', function() {
    $secret_key = "secret";
    $userData = authenticateRequest($secret_key);
    if ($userData['user_role'] === 'admin') {
        include_once 'routes/productRoutes.php';
    }
});


// ________________ Discounts routes ________________
$router->define('GET', '/discounts', function() {
    include_once 'routes/discountRoutes.php';
});

$router->define('POST', '/discounts', function() {
    $secret_key = "secret";
    $userData = authenticateRequest($secret_key);
    var_dump($userData);
    if ($userData['userRole'] === 'admin') {
        include_once 'routes/discountRoutes.php';
    }
});

$router->define('PUT', '/discounts', function() {
    $secret_key = "secret";
    $userData = authenticateRequest($secret_key);
    if ($userData['user_role'] === 'admin') {
        include_once 'routes/discountRoutes.php';
    }
});

$router->define('DELETE', '/discounts', function() {
    $secret_key = "secret";
    $userData = authenticateRequest($secret_key);
    if ($userData['user_role'] === 'admin') {
        include_once 'routes/discountRoutes.php';
    }
});

// ProductCategory routes Tested OK
// http://localhost:8000/categories?products&id=2 to get all products of category 2
// object type to post to the server {"name":"Pantalons","description":"Tous les pantalons"}
$router->define('GET', '/categories', function() {
    ob_start();
    include_once 'routes/productCategoryRoutes.php';
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

$router->define('POST', '/categories', function() {
    $secret_key = "secret";
    $userData = authenticateRequest($secret_key);
    if ($userData['user_role'] === 'admin') {
        include_once 'routes/productCategoryRoutes.php';
    }
});

$router->define('PUT', '/categories', function() {
    $secret_key = "secret";
    $userData = authenticateRequest($secret_key);
    if ($userData['user_role'] === 'admin') {
        include_once 'routes/productCategoryRoutes.php';
    }
});

$router->define('DELETE', '/categories', function() {
    $secret_key = "secret";
    $userData = authenticateRequest($secret_key);
    if ($userData['user_role'] === 'admin') {
        include_once 'routes/productCategoryRoutes.php';
    }
});


$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->handle($method, $path);
?>