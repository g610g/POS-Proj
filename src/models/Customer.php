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

    public function updateCustomer(array $data)
    {

    }

}
