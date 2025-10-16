<?php
namespace App\controllers;

class LandingController{

    public function index(){
        $cwd = getcwd();
        include $cwd . '/src/views/landing.php';
    }
    public function error(){
        $cwd = getcwd();
        include $cwd . '/src/views/error.php';
    }

}
