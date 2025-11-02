<?php

namespace App\controllers;

use App\Auth;
use App\Views;
use App\models\Order;
use App\models\Product;
use NumberFormatter;
use RdKafka\Producer;

class DashboardController
{
    public function index()
    {
        // Logic to display the dashboard
        Auth::requireAuth();
        $products = (new Product())->getAll();
        $productsCount = count($products);
        //Missing todays sales
        $todaySales = (new Order())->getTodaySales();
        $todaySales = array_reduce($todaySales, function ($carry, $item) {
            return $carry + $item['total_amount'];
        }, 0);
        Views::render('dashboard.php', ['total_products' => $productsCount, 'todaySales' => encodePrice($todaySales)]);
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

        $productModel = new Product();
        $orderModel = new Order();

        $sales  = $productModel->getSalesYearly();
        $joinedSales = $orderModel->getJoinedOrderItems();
        $categorizedYearlySales = $this->categorizeYearly($sales);

        //NOTE:: aggregates total quantity sold
        $totalItems = array_reduce($joinedSales, function ($carry, $item) {
            return $carry + $item['quantity'];
        }, 0);

        //NOTE:: aggregates total sales of all orders
        $totalSales = array_reduce($sales, function ($carry, $item) {
            return $carry + $item['total_amount'];
        }, 0);

        //NOTE::formats order date to a more readable date format
        $sales = array_map(function ($sale) {
            $dateTime = new \DateTime($sale['order_date']);
            return array_merge($sale, ['order_date' => $dateTime->format('M j, Y')]);
        }, $sales);
        Views::render('sales.php', ['categorizedSales' => $categorizedYearlySales, 'totalSales' => $totalSales, 'totalItems' => $totalItems, 'sales' => $sales]);

    }

    private function categorizeYearly(array $sales): array
    {
        $groupedSales = [];
        $years = range(date('Y') - 10, date('Y'));

        foreach ($years as $year) {
            $groupedSales[$year] = [];
        }

        foreach ($sales as $sale) {
            $year = date('Y', strtotime($sale['order_date']));
            $groupedSales[$year][] = $sale;

        }
        $groupedSales = array_map(function ($yearSale, $years) {
            return [
                "key" => $years,
                "sales" => $yearSale
            ];
        }, $groupedSales, array_keys($groupedSales));
        return $groupedSales;
    }
}
