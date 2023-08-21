<?php
require __DIR__ . '/../class/Blog.php';
use Blog\Blog;

$Blog = new Blog();

// Set the appropriate headers for JSON response
header('Content-Type: application/json');

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'DELETE') {
    $data = $blogDetails = $Blog->delete($blogID);
    if ($data) {
        http_response_code(200);
        echo json_encode(['error' => false, 'message' => 'Post deleted']);
        exit();
    }
    http_response_code(400);
    echo json_encode(['error' => false, 'message' => 'Post not deleted']);
    exit();
}

if ($method === 'GET') {
    $blogDetails = $Blog->read($blogID);
    if (empty($blogDetails)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Post not found']);
        exit();
    }
    http_response_code(200);
    echo json_encode($blogDetails);
    exit();
}


if ($method === 'POST') {
    // Receive JSON data from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    $title = isset($data['title']) ? htmlspecialchars(trim($data['title'])) : '';
    $blog = isset($data['body']) ? htmlspecialchars(trim($data['body'])) : '';

    if (empty($title) && empty($blog)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Title and Blog body are required']);
        exit();
    }

    if (strlen($title) > 255) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Blog title cannot be longer than 255 characters']);
        exit();
    }

    if (strlen($title) < 10) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Blog title cannot be shorter than 10 characters']);
        exit();
    }

    if (strlen($blog) > 2500) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Blog body cannot be longer than 2500 characters']);
        exit();
    }

    if (strlen($blog) < 10) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Blog body cannot be shorter than 10 characters']);
        exit();
    }

    $Blog->create([$_SESSION['userID'], $title, $blog]);
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Blog created successfully']);
    exit();
}

// Method not allowed
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);