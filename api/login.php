<?php
require __DIR__ . '/../class/User.php';
use User\User;

$User = new User();

// Set the appropriate headers for JSON response
header('Content-Type: application/json');

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Receive JSON data from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    $email = isset($data['email']) ? trim($data['email']) : '';
    $password = isset($data['password']) ? trim($data['password']) : '';

    if (empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode(['success' => 'error', 'message' => 'Email, Password are required']);
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

    $userDetail = $User->getUserByEmail($email);

    if (empty($userDetail)) {
        http_response_code(400);
        echo json_encode(['success' => 'error', 'message' => 'Credential are incorrect']);
        exit();
    }

    if (!password_verify($password, $userDetail['password'])) {
        http_response_code(400);
        echo json_encode(['success' => 'error', 'message' => 'Credential are incorrect']);
        exit();
    }

    $_SESSION['auth'] = true;
    $_SESSION['userID'] = $userDetail['userID'];
    $_SESSION['role'] = $userDetail['role'];
    $_SESSION['name'] = $userDetail['name'];
    http_response_code(200);
    echo json_encode(['success' => 'success', 'message' => 'Login successful!']);
    exit();
}

// Method not allowed
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);