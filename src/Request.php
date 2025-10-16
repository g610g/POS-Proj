<?php

namespace App;

use Exception;
use Respect\Validation\Validator; 

class Request{
    private array $body;
    private array $headers;
    private $query;
    private $method;
    private $path;
    private $requestBody;
    public function  __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $this->headers = $this->parseHeaders();
        $this->requestBody = $this->getRequestBody();
    }
    private function parseHeaders(){
        $headers = [];
        if (function_exists('getallheaders')){
            return getallheaders();
        }

        foreach ($_SERVER as $key => $value){
            if (str_starts_with($key, "HTTP_")){
                $header = str_replace('_', '-', substr($key, 5));
                $headers[$key] = $value;
            }
        }
        return $headers;

    }
    public  function getRequestBody(){
        $contentType = $_SERVER["CONTENT_TYPE"] ?? '';

        if (stripos($contentType, "application/json") !== false){
            $jsonData = file_get_contents("php://input");
            return json_decode($jsonData, true);
        }
        if (stripos($contentType, "application/x-www-form-urlencoded") !== false){
            return $_POST;
        }
        if (stripos($contentType, "multiform/form-data") !== false){
            return $_POST + $_FILES;
        }
        return [];
    }

    public function validate(array $rules):bool{
        foreach ($rules as $key => $rule){
            //this data will validated
            $this->requestBody;
            if (!$rule->isValid($this->requestBody[$key])){
                throw new Exception("Validation fails in {$key}");
            }
        }
        return true;
    }

}
