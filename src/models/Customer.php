<?php

namespace App\models;

class Customer
{
    private $tableName = 'customers';
    private $dbConnection;


    public function __construct()
    {
        $this->dbConnection = (new DB())->getConnection();

    }

    public function createCustomer(string $customerName, ?$connection)
    {
        //If no connection is passed into this model
        //NOTE:: for the purpose that this should be within transaction
        
        if (!$connection){
            $connection = $this->dbConnection;
        }

        $stmt = $connection->prepare("INSERT INTO {$this->tableName} (customer_name, total_items_bought) VALUES (:customer_name, 0)");

        $stmt->bindValue(":customer_name", $customerName);

        return $stmt->execute();
    }
    public function updateCustomer(array $data)
    {

    }

}
