<?php

declare(strict_types=1);
require_once __DIR__ . '/src/globals.php';
require_once __DIR__ . '/src/middleware.php';

use App\controllers\CartController;
use App\controllers\UserController;
use App\controllers\auth_controller;
use App\controllers\DashboardController;
use App\controllers\ProductController;
use App\controllers\LandingController;
use App\TestChild;
use App\Test;
use App\Session;
use App\router;

require __DIR__ . '/vendor/autoload.php';

ob_start(); //buffers the output until the end of the script

//Starting the session on each request
Session::start();
invokeMiddlewares();

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
$router->get('/signup', [auth_controller::class, 'signup']);
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/inventory', [DashboardController::class, 'inventory']);
$router->get('/add-user', [UserController::class, 'show']);
$router->get('/', [LandingController::class, 'index']);
$router->get('/error', [LandingController::class, 'error']);
$router->get('/users', [UserController::class, 'index']);
$router->get('/users/delete', [UserController::class, 'indexDestroy']);
$router->get('/users/edit', [UserController::class, 'indexEdit']);
$router->get('/shop', [ProductController::class, 'index']);
$router->get('/cart', [CartController::class, 'index']);



$router->post('/cart/checkout', [CartController::class, 'checkout']);
$router->post('/cart/remove', [CartController::class, 'removeCartItem']);
$router->post('/cart/add', [ProductController::class, 'addCart']);
$router->post('/delete/user', [UserController::class, 'destroy']);
$router->post('/edit/user', [UserController::class, 'update']);
$router->post('/signin', [auth_controller::class, 'signin']);
$router->post('/signup', [auth_controller::class, 'register']);
$router->post('/add-product', [ProductController::class, 'store']);
$router->post('/edit-product', [ProductController::class, 'update']);
$router->post('/delete-product', [ProductController::class, 'destroy']);
$router->post('/add-user', [UserController::class, 'store']);
$router->post('/logout', [auth_controller::class, 'logout']);

$router->dispatch();

/* consoleLog("buffered output" . ob_get_contents()); */

ob_flush(); //flushes the buffered output since from the start of execution of this script
