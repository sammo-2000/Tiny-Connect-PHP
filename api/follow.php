<?php
require __DIR__ . '/../class/Follow.php';
use Follow\Follow;

$Follow = new Follow();

// Set the appropriate headers for JSON response
header('Content-Type: application/json');

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $result = $Follow->follow($userID);
    if (empty($result)) {
        http_response_code(400);
        echo json_encode(['success' => 'error', 'message' => 'There was an error']);
        exit();
    }

    http_response_code(200);
    echo json_encode(['success' => 'success', 'message' => $result]);
    exit();

}

// Method not allowed
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);