<?php
require __DIR__ . '/../class/User.php';
use User\User;

$User = new User();

// Set the appropriate headers for JSON response
header('Content-Type: application/json');

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'DELETE') {
    $User->delete($_SESSION['userID']);
    require_once __DIR__ . '/../core/logout.php';
    http_response_code(200);
    echo json_encode(['success' => true]);
    exit();
}

if ($method === 'POST') {
    // Receive JSON data from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    $email = isset($data['email']) ? htmlspecialchars(trim($data['email'])) : '';
    $OTP = isset($data['OTP']) ? htmlspecialchars(trim($data['OTP'])) : '';
    $password = isset($data['password']) ? htmlspecialchars(trim($data['password'])) : '';

    if (empty($email)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Email is required']);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) >= 255) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Email is invalid']);
        exit();
    }

    $userDetail = $User->getUserByEmail($email);

    if (empty($userDetail)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No user found with given email']);
        exit();
    }

    if (empty($OTP) && empty($password)) {
        // User sent email only
        $result = $User->sendEmail($email);
        http_response_code(400);
        echo json_encode($result);
        exit();
    }
    if (empty($OTP) || empty($password)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Please provide OTP & new password']);
        exit();
    } else {
        $result = $User->resetPassword($email, $password, $OTP);
        http_response_code($result['code']);
        echo json_encode($result['response']);
        exit();
    }
}

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

if ($method === 'PATCH') {
    // Receive JSON data from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    $image = isset($data['image']) ? trim($data['image']) : '';
    $name = isset($data['name']) ? htmlspecialchars(trim($data['name'])) : '';
    $bio = isset($data['bio']) ? htmlspecialchars(trim($data['bio'])) : '';

    if (!empty($name)) {
        if (strlen($name) > 40) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Name cannot be longer than 40 characters']);
            exit();
        }
        if (strlen($name) < 3) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Name cannot be shorter than 3 characters']);
            exit();
        }
        if (empty($name)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Name cannot be shorter than 3 characters']);
            exit();
        }
        $_SESSION['name'] = $name;
        $User->updateName($_SESSION['userID'], $name);
    }

    if (!empty($bio)) {
        if (strlen($bio) > 255) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Name cannot be longer than 255 characters']);
            exit();
        }
        $User->updateBio($_SESSION['userID'], $bio);
    }

    if (!empty($image)) {
        $image_data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));

        // Validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_buffer($finfo, $image_data);
        finfo_close($finfo);

        if (strpos($mime_type, 'image/') === 0) {
            $image_size_in_bytes = strlen($image) * 3 / 4; // Convert base64 size to bytes
            $max_file_size = 5 * 1024 * 1024; // 5 MB in bytes
            if ($image_size_in_bytes <= $max_file_size) {
                // Image size is within the limit

                // Convert to PNG
                $image_data = imagecreatefromstring($image_data);
                $png_image_path = 'profile-pictures/' . uniqid() . bin2hex(random_bytes(8)) . '.png';

                if (imagepng($image_data, $png_image_path)) {
                    $pathWithLeadingSlash = $User->getCurrentImagePath();
                    $currentImgPath = ltrim($pathWithLeadingSlash, '/');
                    if ($currentImgPath !== null && file_exists($currentImgPath)) {
                        if (unlink($currentImgPath)) {
                            $User->updateImg($_SESSION['userID'], $png_image_path);
                            imagedestroy($image_data); // Free up memory
                        } else {
                            http_response_code(400);
                            echo json_encode(['success' => false, 'message' => 'Failed to delete old image']);
                            exit();
                        }
                    } else {
                        // There's no old image to delete, so proceed with updating the new image
                        $User->updateImg($_SESSION['userID'], $png_image_path);
                        imagedestroy($image_data); // Free up memory
                    }
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

    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Profile updated successfuly']);
    exit();
}

// Method not allowed
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);