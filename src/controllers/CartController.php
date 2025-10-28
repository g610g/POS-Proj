<?php

namespace App\controllers;

use App\Views;

class CartController
{
    public function index(): void
    {
        Views::render('cart.php');
    }
}
