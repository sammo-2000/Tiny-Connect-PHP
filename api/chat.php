<?php
require __DIR__ . '/../class/Chat.php';
use Chat\Chat;

$Chat = new Chat();

// Set the appropriate headers for JSON response
header('Content-Type: application/json');

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (isset($userID)) {
        http_response_code(200);
        echo json_encode($Chat->read($userID));
        exit();
    }
    http_response_code(200);
    echo json_encode($Chat->getAll());
    exit();
}

if ($method === 'POST') {
    // Receive JSON data from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    $userID = isset($data['userID']) ? htmlspecialchars(trim($data['userID'])) : '';
    $chat = isset($data['chat']) ? htmlspecialchars(trim($data['chat'])) : '';

    if (empty($userID) || empty($chat)) {
        http_response_code(400);
        echo json_encode(['error' => true, 'message' => 'Cannot send an empty chat']);
        exit();
    }

    if (strlen($chat) > 255) {
        http_response_code(400);
        echo json_encode(['error' => true, 'message' => 'Chat cannot be longer than 255 characters']);
        exit();
    }

    $Chat->create($userID, $chat);

    http_response_code(200);
    echo json_encode(['error' => false, 'message' => 'chat sent successfully']);
    exit();
}

// Method not allowed
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);