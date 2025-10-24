<?php

namespace App\models;

use App\models\DB;
use Exception;

class Product
{
    public $table = 'products';
    private $dbConnection;
    public function __construct()
    {
        $this->dbConnection = (new DB())->getConnection();
    }
    //inserts product into the database
    public function insert(array $data)
    {
        try {
            $name = trim($data['product_name'] ?? '');
            $price = floatval($data['price'] ?? 0);
            $stock = intval($data['stock'] ?? 0);


            $stmt = $this->dbConnection->prepare("INSERT INTO {$this->table} (product_name, price, stock) VALUES (:product_name, :price, :stock)");
            $stmt->bindValue(':product_name', $name);
            $stmt->bindValue(':price', $price);
            $stmt->bindValue(':stock', $stock);

            $success = $stmt->execute();
            return $success;
        } catch (Exception $e) {
            consoleLog($e->getMessage());
            return false;
        }
    }
    public function getAll()
    {
        $result = $this->dbConnection->query("SELECT * FROM {$this->table}");
        $products = [];

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $products[] = $row;
        }
        return $products;
    }
    public function getSingleById($id)
    {
        $stmt = $this->dbConnection->prepare("SELECT * from {$this->table} WHERE product_id = :id");

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        $result = $stmt->execute();
        if (!$result) {
            throw new Exception("Error here");
        }
        $row = $result->fetchArray(SQLITE3_ASSOC);
        return $row;
    }

    public function updateProduct(array $data, array $product)
    {

        try {

            $stmt = $this->dbConnection->prepare("UPDATE {$this->table} SET price = :price, stock = :stock, product_name = :product_name WHERE product_id = :id");

            $stmt->bindValue(':id', $data['edit_product_id'], SQLITE3_INTEGER);
            $stmt->bindValue(':product_name', $data['edit_product_name'] ?? $product['product_name'], SQLITE3_TEXT);
            $stmt->bindValue(':stock', $data['edit_stock'] ?? $product['stock'], SQLITE3_INTEGER);
            $stmt->bindValue(':price', $data['edit_price'] ?? $product['price'], SQLITE3_INTEGER);

            $result = $stmt->execute();

            return $result;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function deleteProduct(int $productId)
    {

        try {
            $query = "DELETE from {$this->table} where product_id = :product_id";
            $stmt = $this->dbConnection->prepare($query);
            $stmt->bindValue(':product_id', $productId, SQLITE3_INTEGER);

            $result = $stmt->execute();

            return $result;
        } catch (Exception $e) {
            throw new Exception("Error deleting product with product id: {$productId}: {$e->getMessage()}");
        }

    }
}
