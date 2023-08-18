<?php
require __DIR__ . '/../class/User.php';
use User\User;


// Set the appropriate headers for JSON response
header('Content-Type: application/json');

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Receive JSON data from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    $email = isset($data['email']) ? trim($data['email']) : '';
    $password = isset($data['password']) ? trim($data['password']) : '';
    $password_repeat = isset($data['password_repeat']) ? trim($data['password_repeat']) : '';

    if (empty($email) || empty($password) || empty($password_repeat)) {
        http_response_code(400);
        echo json_encode(['success' => 'error', 'message' => 'Email, Password & Password Repeat are required']);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) >= 255) {
        http_response_code(400);
        echo json_encode(['success' => 'error', 'message' => 'Email is invalid']);
        exit();
    }

    if (strlen($password) < 8 || strlen($password) > 255) {
        http_response_code(400);
        echo json_encode(['success' => 'error', 'message' => 'Password must be longer than 8 characters']);
        exit();
    }

    if ($password != $password_repeat) {
        http_response_code(400);
        echo json_encode(['success' => 'error', 'message' => 'Passwords must be matching']);
        exit();
    }

    $User = new User();
    $userDetail = $User->getUserByEmail($email);

    if (!empty($userDetail)) {
        http_response_code(200);
        echo json_encode(['success' => 'error', 'message' => 'User already exists please login instead']);
        exit();
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    $User->create([$email, $password]);
    http_response_code(200);
    echo json_encode(['success' => 'success', 'message' => 'User created successfully']);
    exit();
}

// Method not allowed
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);