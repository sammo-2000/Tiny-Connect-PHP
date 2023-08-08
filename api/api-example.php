<?php
namespace API;

use model\Dbh;

$ExampleAPI = new ExampleAPI();
$ExampleAPI->myFunction();

class ExampleAPI extends Dbh
{
    private $response;
    public function myFunction()
    {
        $this->response = [
            'status' => 'success',
            'message' => 'Example API is ran'
        ];
        http_response_code(200);
        JSON($this->response);
    }
}