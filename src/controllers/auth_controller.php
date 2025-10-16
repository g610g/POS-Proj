<?php

namespace App\controllers;

use App\Request;
use App\Session;
use App\models\DB;
use App\Auth;
use Exception;
use Respect\Validation\Validator; 
class auth_controller {
    private Request $request;

    public  function __construct()
    {
        $this->request = new Request();
    }
    public function showLoginForm(){
        Auth::guest();
        $cwd = getcwd();
        include $cwd . '/src/login.php';
    }

    public function signin(){

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

    public  function signup():void{
        $cwd = getcwd();
        include $cwd . '/src/views/signup.php';
    }

    public function register():void{
        $success = $this->request->validate([
            'username' => Validator::stringVal()->min(1),
            'email' => Validator::email()->min(1),
            'password' => Validator::stringVal()->min(1),
            'confirm_password' => Validator::stringVal()->min(1),
        ]);
        if (!$success){
            Request::redirect('/signup');
        }
        $db = (new DB());
    }
    public function logout():void{
        Session::unset('user');
        Request::redirect('/login');
    }
    
}
