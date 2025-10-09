<?php

namespace App\controllers;

use App\Session;
use App\models\DB;
class auth_controller {
    public static function showLoginForm(){
        $cwd = getcwd();
        include $cwd . '/src/login.php';
    }
    public static function signin(){

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $db = (new DB());

        $result = $db->insertUser($username, $password);

        if ($result){
            echo "Success";
            Session::set('user', [
                'username' => $username
            ]);

            header('Location: /dashboard');
        }else{
            echo "Fail";
        }
    }
}
