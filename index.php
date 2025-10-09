<?php
declare(strict_types=1);

use App\controllers\auth_controller;
use App\controllers\DashboardController;
use App\controllers\ProductController;
use App\Session;
use App\router;
require __DIR__ . '/vendor/autoload.php';

//Starting the session on each request
Session::start();

// Simple router for PHP built-in server
if (php_sapi_name() == "cli-server") {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false; // serve the requested file directly
    }
}
$router = new router();

$router->get('/login', [auth_controller::class, 'showLoginForm']);
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/inventory', [DashboardController::class, 'inventory']);
$router->post('/signin', [auth_controller::class, 'signin']);
$router->post('/add-product', [ProductController::class, 'store']);
$router->post('/edit-product', [ProductController::class, 'update']);

$router->dispatch();




