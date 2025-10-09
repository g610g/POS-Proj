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
}