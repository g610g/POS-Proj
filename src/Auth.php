<?php
namespace App;

use App\Session;
class Auth{
    public static function requireAuth(){
        if (!Session::has('user')){
            header('Location: /login');
            exit;
        }
    }

    public static function user(){
        return Session::get('user');
    }
    public static function guest(){
        if (Session::has('user')){
            /* $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); */
            header("Location: /dashboard");
        }
    }
}
