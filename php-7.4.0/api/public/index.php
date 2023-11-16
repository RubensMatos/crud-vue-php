<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Handle OPTIONS request (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Responde com sucesso (200 OK) e encerra a execução
    header("HTTP/1.1 200 OK");
    exit();
}

require_once __DIR__ . '/../models/Db.php';

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/ProducttypeModel.php';
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/OrderModel.php';

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/ProducttypeController.php';
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../controllers/OrderController.php';

// Crie instâncias de Db, UserModel e AuthController
$db                    = new Db();
$userModel             = new UserModel($db);
$authController        = new AuthController($userModel);

// Crie instâncias de ProducttypeModel e ProducttypeController
$producttypeModel      = new ProducttypeModel($db);
$producttypeController = new ProducttypeController($producttypeModel);

// Crie instâncias de ProductModel e ProductController
$productModel      = new ProductModel($db);
$productController = new ProductController($productModel);

// Crie instâncias de OrderModel e OrderController
$orderModel      = new OrderModel($db);
$orderController = new OrderController($orderModel);

// Roteamento
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Define suas rotas aqui
$routes = [
    ['POST', '/api/auth', [$authController, 'handle']],
    ['GET', '/api/producttype', [$producttypeController, 'handle']],
    ['POST', '/api/producttype', [$producttypeController, 'handle']],
    ['PUT', '/api/producttype', [$producttypeController, 'handle']],
    ['DELETE', '/api/producttype', [$producttypeController, 'handle']],
    ['GET', '/api/product', [$productController, 'handle']],
    ['POST', '/api/product', [$productController, 'handle']],
    ['PUT', '/api/product', [$productController, 'handle']],
    ['DELETE', '/api/product', [$productController, 'handle']],
    ['DELETE', '/api/order', [$orderController, 'handle']],
    ['GET', '/api/order', [$orderController, 'handle']],
    ['POST', '/api/order', [$orderController, 'handle']],
];

$routeFound = false;

foreach ($routes as $route) {
    list($routeMethod, $routePath, $handler) = $route;

    if ($routeMethod == $requestMethod && $routePath == $requestPath) {
        $routeFound = true;
        call_user_func($handler);
        break;
    }
}

// Se nenhuma rota for encontrada, retorne um erro 404
if (!$routeFound) {
    header("HTTP/1.1 404 Not Found");
    echo json_encode(['error' => 'Route not found']);
}