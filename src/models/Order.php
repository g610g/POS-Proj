<?php

namespace App\models;

use PDO;
use PDOException;

class Order
{
    private PDO $dbConnection;
    private string $table = "orders";
    public function __construct()
    {
        $this->dbConnection = (new DB())->getConnection();
    }


    public function createOrder(array $data, ?PDO $externalConnection)
    {
        if ($externalConnection) {
            $this->dbConnection = $externalConnection;
        }

        try {

            $stmt = $this->dbConnection->prepare("INSERT INTO {$this->table} (customer_name, total_amount) VALUES (:customer_name, :total_amount)");

            $stmt->bindValue(":customer_name", $data['customer_name']);
            $stmt->bindValue(":total_amount", $data['total_amount']);

            $stmt->execute();

        } catch (PDOException $e) {
            throw $e;
        }
        return $this->dbConnection->lastInsertId();


    }
    public function getJoinedOrderItems(): array
    {
        $joinQuery = "SELECT * FROM orders
            inner join order_items on order_items.order_id = orders.order_id;";
        try {

            $stmt = $this->dbConnection->prepare($joinQuery);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }
    public function getTodaySales(): array
    {
        $query = "SELECT * from orders WHERE (
            date(order_date) = date('now')
        )";

        try {
            $stmt = $this->dbConnection->query($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }

    }


}
