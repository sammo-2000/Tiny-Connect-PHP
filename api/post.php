<?php
require __DIR__ . '/../class/Post.php';
use Post\Post;

$Post = new Post();

// Set the appropriate headers for JSON response
header('Content-Type: application/json');

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'DELETE') {
    $data = $postDetails = $Post->delete($postID);
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
    $postDetails = $Post->read($postID);
    if (empty($postDetails)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Post not found']);
        exit();
    }
    http_response_code(200);
    echo json_encode($postDetails);
    exit();
}

if ($method === 'POST') {
    // Receive JSON data from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    $userID = isset($data['userID']) ? htmlspecialchars(trim($data['userID'])) : '';
    $caption = isset($data['caption']) ? htmlspecialchars(trim($data['caption'])) : '';
    $image = isset($data['image']) ? trim($data['image']) : '';

    if (empty($userID)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'User ID is required']);
        exit();
    }

    if (empty($caption)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Caption is required']);
        exit();
    }

    if (strlen($caption) > 255) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Caption cannot be longer than 255 characters']);
        exit();
    }

    if (strlen($caption) < 5) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Caption cannot be shorter than 5 characters']);
        exit();
    }

    if (empty($image)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Image is required']);
        exit();
    }

    $image_data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));

    // Validate MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_buffer($finfo, $image_data);
    finfo_close($finfo);

    if (strpos($mime_type, 'image/') === 0) {
        $image_size_in_bytes = strlen($image) * 3 / 4; // Convert base64 size to bytes
        $max_file_size = 5 * 1024 * 1024; // 5 MB in bytes
        if ($image_size_in_bytes <= $max_file_size) {
            // Convert to PNG
            $image_data = imagecreatefromstring($image_data);
            $png_image_path = 'post-pictures/' . uniqid() . bin2hex(random_bytes(8)) . '.png';

            if (imagepng($image_data, $png_image_path)) {
                // Save the data into database
                $Post->create([$userID, $caption, '/' . $png_image_path]);
                http_response_code(200);
                echo json_encode(['success' => true, 'message' => 'Post created successfully']);
                exit();
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Failed to save new image']);
                exit();
            }
        } else {
            // Image size exceeds the limit
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Image size should be smaller than 5MB']);
            exit();
        }
    } else {
        // Invalid image type
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Sorry, image type not allowed']);
        exit();
    }
}

// Method not allowed
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);