<?php

namespace App\models;

use App\models\DB;
use Exception;

class Product{
    public $table = 'products';
    private $dbConnection;
    public function __construct(){
        $this->dbConnection = (new DB())->getConnection();
    }


    //inserts product into the database
    public function insert(array $data){

        $name = trim($data['product_name'] ?? '');
        $price = floatval($data['price'] ?? 0);
        $stock = intval($data['stock'] ?? 0);


        $stmt = $this->dbConnection->prepare("INSERT INTO {$this->table} (product_name, price, stock) VALUES (:product_name, :price, :stock)");
        $stmt->bindValue(':product_name', $name);
        $stmt->bindValue(':price', $price);
        $stmt->bindValue(':stock', $stock);

        $success = $stmt->execute();
        var_dump($success);
        return $success;
    }
    public function getAll(){
        $result = $this->dbConnection->query("SELECT * FROM {$this->table}");
        $products = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $products[] = $row;
        }
        return $products;
    }
    public  function getSingleById($id){
        $stmt = $this->dbConnection->prepare("SELECT * from {$this->table} WHERE product_id = :id");

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        
        $result = $stmt->execute();
        if (!$result){
            throw new Exception("Error here");
        }
        $row = $result->fetchArray(SQLITE3_ASSOC);
        return $row;
    }
    public function  updateProduct(array $data){
        $stmt = $this->dbConnection->prepare("UPDATE {$this->table} SET price = :price, stock = :stock WHERE product_id = :id");
        $stmt->bindValue(':id', $data['product_id'], SQLITE3_INTEGER);
        $stmt->bindValue(':stock', $data['stock'], SQLITE3_INTEGER);
        $stmt->bindValue(':price', $data['price'], SQLITE3_INTEGER);
    }
}
