<?php

class Middleware{
    private $value;
    public function __construct(string $value)
    {
        $this->value = $value;

    }
    public function __invoke(string $input, callable $next): string
    {
        $output = $next($this->value . $input);
        echo $this->value . '\n';

        return $output . $this->value;
    }
}

function invokeMiddlewares(){
    $middlewares = [
        new Middleware('1'),
        new Middleware('2'),
        new Middleware('3'),
    ];
    $action = fn(string $input):string => $input;

    foreach ($middlewares as $middleware){
        $action = fn(string $input):string => $middleware($input, $action);
    }

    echo $action('value');
}

