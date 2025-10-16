<?php
use App\Session;

//this function is intented for retrieving validation errors
function errors(?string $key = null):string | null {
    
    $validationErrors = Session::get('validation');
    if (!$key){
        return $validationErrors;
    }
    return $validationErrors[$key] ?? null;
}
