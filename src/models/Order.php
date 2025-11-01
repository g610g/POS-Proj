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
}
