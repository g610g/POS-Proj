<?php

namespace App\controllers;

use App\Session;
use App\models\DB;
use App\Auth;
class auth_controller {
    public static function showLoginForm(){
        Auth::guest();
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

    public function logout():void{
        Session::unset('user');
        header('Location: /login');
    }
}
