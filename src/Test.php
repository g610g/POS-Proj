<?php
namespace App;

class Test{

    public static function who(){
        echo 'Test';
    }

    public static function tell(){
        echo static::who();
    }

}

class TestChild extends Test{
    public static function who(){
        echo "TestChild";
    }

}
