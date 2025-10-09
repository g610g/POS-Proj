<?php
namespace App\models;

use Exception;

//Database instance here
class DB{

    private $database;

    public function __construct($dbFile = "./../../database/database.db")
    {
        try{

            $cwd = getcwd();
            $dbFile = $cwd . '/database/database.db';
            $this->database = new \SQLite3($dbFile);
        }catch(Exception $e ){
            die("Database connection failed: " .  $e->getMessage());
        }
    }
    public  function getConnection(){
        return $this->database;
    }
    public function  insertUser(string $username, string $password){

        $username = trim($username);
        $password = trim($password);

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->database->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', $passwordHash);

        return $stmt->execute();
    }

}
