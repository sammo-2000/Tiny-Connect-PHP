<?php
require __DIR__ . '/../class/Home.php';
use Home\Home;

$Home = new Home();

// Set the appropriate headers for JSON response
header('Content-Type: application/json');

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if ($type === 'post') {
        http_response_code(200);
        echo json_encode($Home->PublicPosts($limit));
        exit();
    }

    if ($type === 'blog') {
        http_response_code(200);
        echo json_encode($Home->PublicBlogs($limit));
        exit();
    }

    if ($type === 'postFollowing') {
        http_response_code(200);
        echo json_encode($Home->PrivatePosts($limit));
        exit();
    }

    if ($type === 'blogFollowing') {
        http_response_code(200);
        echo json_encode($Home->PrivateBlogs($limit));
        exit();
    }
}

// Method not allowed
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);