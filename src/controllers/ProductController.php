<?php

namespace App\controllers;

use App\Request;
use App\Views;
use App\models\Product;
use Exception;
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
        Views::render('shop.php');
    }
    //we should return back and add validation for this one
    public function store()
    {

        //validate input fields
        $success = $this->request->validate([
            'product_name' => Validator::stringVal()->min(1),
            'stock' => Validator::intType(),
            'price' => Validator::floatType()
        ]);
        consoleLog("product insert validation result" . json_encode($this->request->validated()));
        if (!$success) {
            echo "Output before header is called";
            Request::redirect('/inventory');
        }

        // Simulate successful database insertion
        (new Product())->insert($this->request->validated());
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
}
