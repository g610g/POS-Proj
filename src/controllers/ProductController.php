<?php
namespace App\controllers;

use App\models\Product;
use Exception;

class ProductController{
    

    //we should return back and add validation for this one
    public function store(){

        $productName = $_POST['product_name'] ?? '';
        $price = $_POST['price'] ?? 0;
        $stock = $_POST['stock'] ?? 0;

       

        if ($productName && $price > 0 && $stock >= 0) {
            // Simulate successful database insertion
            (new Product())->insert([
            'product_name' => $productName,
            'price' => $price,
            'stock' => $stock
                ]);
            // Redirect back to inventory page
            header('Location: /inventory');
        } else {
            //I should throw exception here
            echo "Failed to add product. Please ensure all fields are filled correctly.";
        }
    }
    public function update(){

        $productId = $_POST['edit_product_id'];

        $editProductData = [
            'product_id' => $productId,
            'product_name' => $_POST['edit_product_name'],
            'price' => $_POST['edit_price'],
            'stock' => $_POST['edit_stock'], 
        ];
        $productModel = new Product();
        $product = $productModel->getSingleById($productId);
        try{

            $updated = $productModel->updateProduct($editProductData);

            if (!$updated){
                throw new Exception("Unsuccesful update of product with product name {$editProductData['product_name']}");
            }
        }catch (\Exception $e){
            echo "Error updating product: " . $e->getMessage();
            header("Location: /error");
        }
        header("Location: /inventory");
    }
    public function destroy():void{
        $productId = $_POST['delete_product_id'];

        try{
            (new Product())->deleteProduct($productId);
        }catch(Exception $e){
            echo "Error updating product: " . $e->getMessage();
            header("Location: /error");
        }

        header("Location: /inventory");

    }
}
