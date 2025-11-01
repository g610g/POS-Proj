<?php

namespace App\models;

use App\models\DB;
use Exception;
use PDO;

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

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSingleById($id)
    {
        $stmt = $this->dbConnection->prepare("SELECT * from {$this->table} WHERE product_id = :id LIMIT 1");

        $result = $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    public function updateProduct(array $data, array $product, ?PDO $dbConnection = null)
    {
        if ($dbConnection) {
            $this->dbConnection = $dbConnection;
        }

        try {
            $stmt = $this->dbConnection->prepare("UPDATE {$this->table} SET price = :price, stock = :stock, product_name = :product_name WHERE product_id = :id");
            $result = $stmt->execute([
                ':id' => $data['edit_product_id'],
                ':product_name' => $data['edit_product_name'],
                ':stock' => $data['edit_stock'],
                ':price' => $data['edit_price'],
            ]);
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

    public function getSalesDaily(?string $month = null)
    {
        $query = "SELECT * from orders WHERE (
            (order_date)>= datetime('now', 'start of month')
            AND
            (order_date) < datetime('now','start of month', '+1 month')
        )";
        try {

            $stmt = $this->dbConnection->query($query);
            if ($stmt === false) {
                throw new Exception("false statement in sales daily");
            }

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;

        } catch (Exception $e) {
            throw $e;
        }
    }
    public function getSalesMonthly(?string $month = null)
    {
        $query = "SELECT * from orders WHERE (
            (order_date)>= datetime('now', 'start of year')
            AND
            (order_date) < datetime('now','start of year', '+1 year')
        )";
        try {
            $stmt = $this->dbConnection->query($query);
            if ($stmt === false) {
                throw new Exception("false statement in sales daily");
            }

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;

        } catch (Exception $e) {
            throw $e;
        }
    }
}
