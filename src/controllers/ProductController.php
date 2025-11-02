<?php

namespace App\controllers;

use App\Request;
use App\Session;
use App\Views;
use App\models\Product;
use Exception;
use NumberFormatter;
use Respect\Validation\Validator;

class ProductController
{
    private $request;


    public function __construct()
    {
        $this->request = new Request();
    }
    public function index()
    {
        $productModel = new Product();
        $products = $productModel->getAll();
        $formatter = new NumberFormatter('en_PH', NumberFormatter::CURRENCY);

        $products = array_map(function ($product) use ($formatter) {
            $formattedPrice = $formatter->formatCurrency($product['price'], 'PHP');
            return array_merge($product, ['price' => $formattedPrice, 'product_name' => ucfirst($product['product_name'])]);
        }, $products);

        $products = array_filter($products, function ($product) {
            return $product['stock'] > 0;
        });
        Views::render('shop.php', ['products' => $products]);
    }
    //we should return back and add validation for this one
    public function store()
    {

        //validate input fields
        $success = $this->request->validate([
            'product_name' => Validator::stringVal()->min(1),
            'stock' => Validator::intType(),
            'price' => Validator::floatType(),
            'product_image' => Validator::file()
                            ->image()
                            ->size('1MB', '2MB')
        ]);

        $validated = $this->request->validated();
        consoleLog("product insert validation result" . json_encode($validated));

        if (!$success) {
            echo "Output before header is called";
            consoleLog(json_encode(Session::get('validation')));
            Request::redirect('/inventory');
        }

        //NOTE:: process the uploaded product picture
        $successFile = $this->processUploadedFile($validated['product_image']);
        if (is_string($successFile)) {
            $validated['product_image'] = $successFile;
        } else {
            consoleLog("File not moved");
            $validated['product_image'] = null;
        }

        // Simulate successful database insertion
        $sucessInsert = (new Product())->insert($validated);
        if (!$sucessInsert) {
            Session::set('error', 'Error during insertion of product');
            Request::redirect('/inventory');

        }
        // Redirect back to inventory page
        Request::redirect('/inventory');
    }
    public function update(): void
    {

        $success = $this->request->validate([
            'edit_product_id' => Validator::stringVal()->min(1),
            'edit_product_name' => Validator::nullable(Validator::stringVal()->min(1)),
            'edit_price' => Validator::nullable(Validator::floatType()),
            'edit_stock' => Validator::nullable(Validator::intType()),
        ]);


        $editProductData = $this->request->validated();
        consoleLog("edit product data" . json_encode($editProductData));

        $productModel = new Product();
        $product = $productModel->getSingleById($editProductData['edit_product_id']);

        try {

            $updated = $productModel->updateProduct($editProductData, $product);

            if (!$updated) {
                throw new Exception("Unsuccessful update of product with product name {$editProductData['edit_product_name']}");
            }
        } catch (\Exception $e) {
            echo "Error updating product: " . $e->getMessage();
            Request::redirect('/error');
            exit(1);
        }

        Request::redirect('/inventory');
    }
    public function destroy(): void
    {
        $productId = $_POST['delete_product_id'];

        $this->request->validate([
            'delete_product_id' => Validator::stringVal()->min(1),
        ]);

        $data = $this->request->validated();
        try {
            (new Product())->deleteProduct($data['delete_product_id']);
        } catch (Exception $e) {
            echo "Error updating product: " . $e->getMessage();
            header("Location: /error");
            Request::redirect('/error');
            exit(1);
        }

        Request::redirect('/inventory');

    }
    public function addCart(): void
    {

        $productModel = new Product();
        $success = $this->request->validate([
            'product_id' => Validator::intType()->min(1),
            'quantity' => Validator::intType()->min(1),
        ]);

        $addCartData = $this->request->validated();

        //check product quantity first
        $product = $productModel->getSingleById($addCartData['product_id']);

        if (!isset($product) || !is_array($product)) {
            Session::set('error', 'Product not found in the database');
            Request::redirect('/shop'); //TODO:: redirect should already stop the execution of this script?
            return;
        }
        if ($product['stock'] < $addCartData['quantity']) {
            Session::set('error', 'Product quantity is insufficient');
            Request::redirect('/shop'); //TODO:: redirect should already stop the execution of this script?
            return;
        }

        $cartData = Session::get('cart') ?? []; //NOTE:: cart is an array of products where each product element is composed of [id, quantity, total_amount]

        $existingProduct = $cartData[$addCartData['product_id']] ?? [];

        $newTotalQuantity = ($existingProduct['quantity'] ?? 0) + $addCartData['quantity'];

        $newTotalAmount =  ($existingProduct['total_amount'] ?? 0.0) + ($addCartData['quantity'] * $product['price']);

        $existingProduct = array_merge($existingProduct, ['product_id' => $addCartData['product_id'], 'quantity' => $newTotalQuantity, 'total_amount' => $newTotalAmount, 'product_name' => $product['product_name'], 'price' => $product['price']]);

        //NOTE:: update cart data
        $cartData[$addCartData['product_id']] = $existingProduct;

        $totalPrice = array_reduce($cartData, function (float $carry, array $item) {
            return $carry + $item['total_amount'];
        }, 0.0);

        Session::set('cart', $cartData);

        //NOTE:: we will only update product stocks when this is checkedout

        Request::redirect('/shop');
    }
    public function sales()
    {
        $productModel = (new Product());
        //NOTE:: Check view first. View will be one of the following: Yearly, Monthly, and Daily
        header("Content-Type: application/json; charset=utf-8"); //NOTE::we want to return json

        try {
            $view = $this->request->query('view');
            switch ($view) {
                case 'daily':
                    try {
                        $dailySales = $productModel->getSalesDaily();
                        $groupedDailySales = $this->categorizeDay($dailySales);
                    } catch (Exception $e) {
                        consoleLog($e->getMessage());
                    }
                    echo json_encode($groupedDailySales);
                    exit;
                case 'monthly':
                    try {
                        $monthlySales = $productModel->getSalesMonthly();
                        $groupedMonthlySales = $this->categorizeMonth($monthlySales);
                    } catch (Exception $e) {
                        consoleLog($e->getMessage());
                    }
                    echo json_encode($groupedMonthlySales);
                    exit;
                case 'yearly':
                    try {
                        $yearlySales = $productModel->getSalesYearly();
                        $groupedYearlySales = $this->categorizeYearly($yearlySales);
                    } catch (Exception $e) {
                        consoleLog($e->getMessage());
                    }
                    echo json_encode($groupedYearlySales);
                    exit;
            }

        } catch (Exception $e) {
            consoleLog("Error in sales" . $e->getMessage()); //NOTE:: Error here
        }
    }
    private function categorizeDay(array $dailySales)
    {
        $groupedSales = [];
        for ($d = 1; $d <= 30; $d++) {
            $groupedSales[$d] = [];
        }
        foreach ($dailySales as $sale) {
            $day = (int)date('d', strtotime($sale['order_date']));

            $groupedSales[$day][] = $sale;
        }
        $groupedSales = array_map(function ($sales, $key) {
            return [
                "key" => $key,
                "sales" => $sales,
            ];
        }, $groupedSales, array_keys($groupedSales));

        return $groupedSales;
    }

    private function categorizeMonth(array $sales): array
    {

        $groupedSales = [];

        for ($m = 1; $m <= 12; $m++) {
            $month = date('M', mktime(0, 0, 0, $m, 1, date('Y')));
            $groupedSales[$month] = [];
        }
        foreach ($sales as $sale) {
            $month = date('M', strtotime($sale['order_date']));
            $groupedSales[$month][] = $sale;
        }
        $groupedSales = array_map(function ($monthSale, $monthKey) {
            return [
                "key" => $monthKey,
                "sales" => $monthSale
            ];
        }, $groupedSales, array_keys($groupedSales));
        return $groupedSales;
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

    private function processUploadedFile($file)
    {
        $cwd = getcwd();

        consoleLog(json_encode($file));
        $originalFilename = $file['name'];
        $tmpPath = $file['tmp_name'];
        $assetDir = "/public/assets/";

        if (move_uploaded_file($tmpPath, $cwd . $assetDir . $originalFilename)) {
            return $assetDir . $originalFilename;
        }
        return false;
    }
}
