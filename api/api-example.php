<?php
namespace controller;

use model\Dbh;

$request = $_SERVER['REQUEST_METHOD'];

$ExampleContro = new ExampleContro();

$ExampleContro->notSupported();

$ExampleContro->output();

class ExampleContro extends Dbh
{
    private $response;

    public function notSupported()
    {
        $this->response = [
            'error_code' => 405,
            'status' => 'error',
            'message' => 'Bad Request: Invalid parameters provided',
            'currentMethod' => $_SERVER['REQUEST_METHOD']
        ];
    }

    public function output()
    {
        JSON($this->response);
    }
}