<?php

namespace App;

use Exception;
use Respect\Validation\Validator;
use Respect\Validation\Exceptions\ValidationException;

class Request
{
    private array $body;
    private array $headers;
    private $query;
    private $method;
    private $path;
    private $requestBody;
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $this->headers = $this->parseHeaders();
        $this->requestBody = $this->getRequestBody();
    }
    private function parseHeaders()
    {
        $headers = [];
        if (function_exists('getallheaders')) {
            return getallheaders();
        }

        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, "HTTP_")) {
                $header = str_replace('_', '-', substr($key, 5));
                $headers[$key] = $value;
            }
        }
        return $headers;

    }

    public function validated()
    {
        return $this->requestBody;
    }
    //gets the query parameter value from the current url string
    //This currently works when the request is a GET method request
    public function query(string $key)
    {
        if ($this->method !== 'GET') {
            throw new Exception("Request query method currently works on GET method request only");
        }
        return $_GET[$key];
    }
    public function getRequestBody()
    {
        $contentType = $_SERVER["CONTENT_TYPE"] ?? '';

        if (stripos($contentType, "application/json") !== false) {
            $jsonData = file_get_contents("php://input");
            return json_decode($jsonData, true);
        }
        if (stripos($contentType, "application/x-www-form-urlencoded") !== false) {
            return $_POST;
        }
        if (stripos($contentType, "multiform/form-data") !== false) {
            return $_POST + $_FILES;
        }
        return [];
    }

    public function validate(array $rules): bool
    {
        $hasFailed = false;
        $validationErrors = [];
        foreach ($rules as $key => $rule) {
            try {
                $rule->assert($this->requestBody[$key]);
            } catch (ValidationException $e) {
                $validationErrors[$key] = $e->getFullMessage();
                $hasFailed = true;
            }
        }
        $validationErrors['hasRead'] = false;
        if ($hasFailed) {
            Session::set('validation', $validationErrors);
        }
        return !$hasFailed;
    }
    public static function redirect(string $uri): void
    {
        header("Location: {$uri}");
    }

}
