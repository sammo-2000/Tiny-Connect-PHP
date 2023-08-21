<?php
require __DIR__ . '/../class/Search.php';
use Search\Search;

$Search = new Search();

// Set the appropriate headers for JSON response
header('Content-Type: application/json');

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (isset($search)) {
        $result = $Search->getAll($search);
    } else {
        $result = $Search->getAll();
    }
    http_response_code(200);
    echo json_encode(['success' => true, 'users' => $result]);
    exit();

}

// Method not allowed
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);