<?php

namespace App\models;

use Exception;

//Database instance here
class DB
{
    private $database;

    public function __construct($dbFile = "./../../database/database.db")
    {
        try {

            $cwd = getcwd();
            $dbFile = $cwd . '/database/database.db';
            $this->database = new \SQLite3($dbFile);
        } catch (Exception $e) {
            die("Database connection failed: " .  $e->getMessage());
        }
    }
    public function getConnection()
    {
        return $this->database;
    }

    public function retrieveUser(string $usernameEmail, string $password): ?array
    {
        $usernameEmail = trim($usernameEmail);
        $password = trim($password);

        $stmt = $this->database->prepare("SELECT * FROM users WHERE username = :usernameEmail OR email = :usernameEmail LIMIT 1");
        $stmt->bindValue(':usernameEmail', $usernameEmail);

        $result = $stmt->execute();

        $row = $result->fetchArray(SQLITE3_ASSOC);
        return $row;
    }
    public function createUser(string $username, string $password, string $email)
    {
        $username = trim($username);
        $email = trim($email);
        $password = trim($password);

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $this->database->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $passwordHash);

        return $stmt->execute();

    }

}
