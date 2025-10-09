<?php
namespace App\controllers;

use App\models\Product;

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


        $editProductName= $_POST['edit_product_name'];
        $editProductPrice= $_POST['edit_price'];
        $editProductStock= $_POST['edit_stock'];
        
        $product = (new Product())->getSingleById($productId);

        header("Location: /inventory");
    }
}
