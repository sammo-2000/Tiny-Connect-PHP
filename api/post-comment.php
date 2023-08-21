<?php
require __DIR__ . '/../class/Post-Comment.php';
use Post\Comment;

$PostComment = new Comment();

// Set the appropriate headers for JSON response
header('Content-Type: application/json');

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'DELETE') {
    $result = $PostComment->delete($commentID);
    if ($result) {
        http_response_code(200);
        echo json_encode(['error' => false, 'message' => 'Comment deleted successfully']);
        exit();
    }
    http_response_code(400);
    echo json_encode(['error' => true, 'message' => 'Comment does not belong to you, cannot delete']);
    exit();
}

if ($method === 'POST') {
    // Receive JSON data from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    $comment = isset($data['input']) ? htmlspecialchars(trim($data['input'])) : '';
    $postID = isset($data['postID']) ? htmlspecialchars(trim($data['postID'])) : '';

    if (empty($postID) || empty($comment)) {
        http_response_code(400);
        echo json_encode(['error' => true, 'message' => 'Comment cannot be empty']);
        exit();
    }

    if (strlen($comment) > 255) {
        http_response_code(400);
        echo json_encode(['error' => true, 'message' => 'Comment cannot be longer than 255 characters']);
        exit();
    }

    $PostComment->create([$_SESSION['userID'], $postID, $comment]);
    http_response_code(200);
    echo json_encode(['error' => false, 'message' => 'Comment added successfully']);
    exit();
}

// Method not allowed
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);