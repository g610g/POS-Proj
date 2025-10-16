<?php
declare(strict_types=1);

use App\controllers\auth_controller;
use App\controllers\DashboardController;
use App\controllers\ProductController;
use App\controllers\LandingController;
use App\TestChild;
use App\Test;
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
// var_dump($_SERVER);

$router = new router();

$router->get('/login', [auth_controller::class, 'showLoginForm']);
$router->get('/signup', [auth_controller::class, 'signup']);
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/inventory', [DashboardController::class, 'inventory']);
$router->get('/', [LandingController::class, 'index']);
$router->get('/error', [LandingController::class, 'error']);
$router->post('/signin', [auth_controller::class, 'signin']);
$router->post('/signup', [auth_controller::class, 'register']);
$router->post('/add-product', [ProductController::class, 'store']);
$router->post('/edit-product', [ProductController::class, 'update']);
$router->post('/delete-product', [ProductController::class, 'destroy']);
$router->post('/logout', [auth_controller::class, 'logout']);

$router->dispatch();




