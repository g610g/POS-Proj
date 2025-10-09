<?php
namespace App;

use App\models\DB;

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$db = (new DB());

$result = $db->insertUser($username, $password);

if ($result){
    echo "Success";
}else{
    echo "Fail";
}


