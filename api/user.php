<?php
require __DIR__ . '/../class/User.php';
use User\User;

$User = new User();

// Set the appropriate headers for JSON response
header('Content-Type: application/json');

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $userID = isset($userID) ? trim($userID) : null;

    if ($userID !== null) {
        if (is_numeric($userID)) {
            $userID = (int) $userID;
        } else {
            $userID = 0;
        }

        $userDetail = $User->read($userID);

        if ($userDetail === null) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            exit();
        }

        $User->getFollow($userDetail); 

        http_response_code(200);
        echo json_encode($userDetail);
        exit();
    }
}

// Method not allowed
http_response_code(405);
echo json_encode(['error' => 'Method not allowed', 'method' => $method]);