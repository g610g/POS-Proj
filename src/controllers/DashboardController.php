<?php

namespace App\controllers;

use App\Auth;
use App\Views;
use App\models\Product;
use NumberFormatter;

class DashboardController
{
    public function index()
    {
        // Logic to display the dashboard
        Auth::requireAuth();
        $products = (new Product())->getAll();
        $productsCount = count($products);
        //Missing todays sales
        Views::render('dashboard.php', ['total_products' => $productsCount]);
    }
    public function inventory()
    {
        Auth::requireAuth();
        //retrieve products data here
        $products = (new Product())->getAll();
        $formatter = new NumberFormatter('en_PH', NumberFormatter::CURRENCY);
        $products = array_map(function ($product) use ($formatter) {
            $formattedPrice = $formatter->formatCurrency($product['price'], 'PHP');
            return array_merge($product, ['price' => $formattedPrice, 'product_name' => ucfirst($product['product_name'])]);
        }, $products);


        Views::render('inventory.php', ['products' => $products]);
    }
    public function sales()
    {
        Views::render('sales.php');

    }
}
