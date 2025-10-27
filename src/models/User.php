<?php

namespace App\models;

use Exception;

class User
{
    public $table = 'users';
    private $dbConnection;
    public function __construct()
    {
        $this->dbConnection = (new DB())->getConnection();
    }

    public function getUsers()
    {

        $result = $this->dbConnection->query("SELECT email, username, id from {$this->table}");
        $users = [];
        while ($user = $result->fetchArray(SQLITE3_ASSOC)) {
            $users[] = $user;
        }

        return $users;

    }
    public function addUser(array $data)
    {
        try {
            $stmt = $this->dbConnection->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT); //uses bcrypt algorithm for hashing the password
            $stmt->bindValue(':username', $data['username']);
            $stmt->bindValue(':email', $data['email']);
            $stmt->bindValue(':password', $hashedPassword);
            $result = $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error adding new user: {$e->getMessage()}"); //must add a proper error handling error
        }

    }

    public function deleteUser($userId): void
    {
        try {
            $stmt = $this->dbConnection->prepare("DELETE FROM {$this->table} WHERE id = :id"); //Deletes single user in the database using the following sql statement
            $stmt->bindValue(':id', $userId);
            $result = $stmt->execute();

            //manually throw error in query error
            if ($result === false) {
                throw new Exception("Sqlite query error in deleting user with user id {$userId}: {$this->dbConnection->lastErrorMsg()}");
            }
        } catch (Exception $e) {
            /* throw new Exception("Error deleting user: {$e->getMessage()}"); //must add a proper error handling error */
            throw $e;
        }
    }

    public function updateUser($data): void
    {
        $stmt = $this->dbConnection->prepare("UPDATE {$this->table} SET 
                username = :username,
                email = :email
            WHERE 
                id = :id
            "); //Updates a single record in the database where the id matches the id queried
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':username', $data['username']);
        $stmt->bindValue(':id', $data['id']);

        $result = $stmt->execute();

        //manually throw error in query error
        if ($result === false) {
            throw new Exception("Sqlite query error in deleting user with user id {$data['id']}: {$this->dbConnection->lastErrorMsg()}");
        }
    }

}
