<?php
namespace User;

use Model\Dbh;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class User extends Dbh
{

    // Sign up page
    public function create(array $data)
    {
        // Implement your create logic here
        $this->db('INSERT INTO `user`(`email`, `password`) VALUES (?, ?)', $data);
    }

    public function read(int $id)
    {
        $user = $this->fetch('SELECT `userID`, `name`, `image`, `joinedAt`, `bio` FROM `user` WHERE `userID` = ?', [$id]);

        if (empty($user)) {
            http_response_code(400);
            echo json_encode(['error' => true, 'message' => 'no user was found']);
            exit();
        }

        $posts = $this->fetchAll('SELECT * FROM `post` WHERE `uploaderID` = ? ORDER BY `postID` DESC', [$id]);
        $blogs = $this->fetchAll('SELECT * FROM `blog` WHERE `uploaderID` = ? ORDER BY `blogID` DESC', [$id]);

        $follower = $this->fetch('SELECT COUNT(*) AS `follower` FROM `follow` WHERE `followingID` = ?', [$id]);
        $followerCount = $follower ? $follower['follower'] : 0;

        $following = $this->fetch('SELECT COUNT(*) AS `following` FROM `follow` WHERE `followID` = ?', [$id]);
        $followingCount = $following ? $following['following'] : 0;

        $postCount = $posts ? count($posts) : 0;
        $blogCount = $blogs ? count($blogs) : 0;

        $meThems = $this->fetchAll('SELECT `followingID` FROM `follow` WHERE `followID` = ?', [$id]);

        $theirDetailmeThem = [];

        foreach ($meThems as $meThem) {
            $theirDetailmeThem[] = $this->fetch('SELECT `userID`, `name`, `image` FROM `user` WHERE `userID` = ?', [$meThem['followingID']]);
        }

        $themsMe = $this->fetchAll('SELECT `followID` FROM `follow` WHERE `followingID` = ?', [$id]);

        $theirDetailThemMe = [];

        foreach ($themsMe as $themMe) {
            $theirDetailThemMe[] = $this->fetch('SELECT `userID`, `name`, `image` FROM `user` WHERE `userID` = ?', [$themMe['followID']]);
        }

        $isFollowing = $this->fetch('SELECT * FROM `follow` WHERE `followID` = ? AND `followingID` = ?', [$_SESSION['userID'], $id]);

        if (empty($isFollowing)) {
            $isFollowing = false;
        } else {
            $isFollowing = true;
        }

        if ($id == $_SESSION['userID']) {
            $isFollowing = 'ME';
        }

        return [
            'user' => $user,
            'posts' => $posts,
            'blogs' => $blogs,
            'followers' => $followerCount,
            'following' => $followingCount,
            'postCount' => $postCount,
            'blogCount' => $blogCount,
            'isFollowing' => $isFollowing,
            'follow_details' => [
                'me-them' => $theirDetailmeThem,
                'them-me' => $theirDetailThemMe
            ]
        ];
    }

    public function delete(int $id)
    {
        $images = $this->fetchAll('SELECT * FROM `post` WHERE `uploaderID` = ?', [$id]);
        foreach ($images as $image) {
            unlink($image['image']);
        }
        $user = $this->fetch('SELECT * FROM `user` WHERE `userID` = ?', [$id]);
        unlink($user['image']);
        $this->db('DELETE FROM `post` WHERE `uploaderID` = ?', [$id]);
        $this->db('DELETE FROM `blog-comment` WHERE `userID` = ?', [$id]);
        $this->db('DELETE FROM `chat` WHERE `senderID` = ? OR `receiverID` = ?', [$id, $id]);
        $this->db('SELECT * FROM `chat-recent` WHERE `senderID` = ? OR `receiverID` = ?', [$id, $id]);
        $this->db('DELETE FROM `post-comment` WHERE `userID` = ?', [$id]);
        $this->db('DELETE FROM `blog` WHERE `uploaderID` = ?', [$id]);
        $this->db('DELETE FROM `follow` WHERE `followID` = ? OR `followingID` = ?', [$id, $id]);
        $this->db('DELETE FROM `user` WHERE `userID` = ?', [$id]);
    }

    public function getAll()
    {
        // Implement your get all logic here
    }

    // For the login API
    public function getUserByEmail(string $email)
    {
        return $this->fetch('SELECT * FROM `user` WHERE `email` = ?', [$email]);
    }

    // For profile page, to get list of follow and following
    public function getFollow(array $userDetail)
    {
        $userID = $userDetail['user']['userID'];
        $meThems = $this->fetchAll('SELECT `followingID` FROM `follow` WHERE `followID` = ?', [$userID]);

        $theirDetailmeThem = [];

        foreach ($meThems as $meThem) {
            $theirDetailmeThem[] = $this->fetch('SELECT `userID`, `name`, `image` FROM `user` WHERE `userID` = ?', [$meThem['followingID']]);
        }

        $themsMe = $this->fetchAll('SELECT `followID` FROM `follow` WHERE `followingID` = ?', [$userID]);

        $theirDetailThemMe = [];

        foreach ($themsMe as $themMe) {
            $theirDetailThemMe[] = $this->fetch('SELECT `userID`, `name`, `image` FROM `user` WHERE `userID` = ?', [$themMe['followID']]);
        }

        $userDetail['follow_details'] = [
            'me-them' => $theirDetailmeThem,
            'them-me' => $theirDetailThemMe
        ];

        return $userDetail;

    }

    // Profile edit
    public function updateImg(string $id, string $image)
    {
        $this->db('UPDATE `user` SET `image`= ? WHERE `userID` = ?', ['/' . $image, $id]);
    }

    public function updateName(string $id, string $name)
    {
        $this->db('UPDATE `user` SET `name`= ? WHERE `userID` = ?', [$name, $id]);
    }

    public function updateBio(string $id, string $bio)
    {
        $this->db('UPDATE `user` SET `bio`= ? WHERE `userID` = ?', [$bio, $id]);
    }
    public function getCurrentImagePath()
    {
        $result = $this->fetch('SELECT `image` FROM `user` WHERE `userID` = ?', [$_SESSION['userID']]);
        return $result['image'];
    }

    // Password reset 
    public function resetPassword(string $email, string $password, string $OTP)
    {
        $result = $this->fetch('SELECT * FROM `password` WHERE `email` = ?', [$email]);

        if ($OTP == $result['OTP']) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $this->db('UPDATE `user` SET `password` = ? WHERE `email` = ?', [$password, $email]);
            $this->db('DELETE FROM `password` WHERE `email` = ?', [$email]);
            return [
                'code' => '200',
                'response' => [
                    'success' => true,
                    'message' => 'Pasword reset successful, you can now login'
                ]
            ];
        }
        $this->db('DELETE FROM `password` WHERE `email` = ?', [$email]);
        $this->sendEmail($email);
        return [
            'code' => '400',
            'response' => [
                'success' => false,
                'message' => 'Sorry, OTP incorrect - new one sent to your email now'
            ]
        ];
    }

    public function sendEmail(string $email)
    {
        $this->db('DELETE FROM `password` WHERE `time` <= NOW() - INTERVAL 30 MINUTE', []);
        $result = $this->fetch('SELECT * FROM `password` WHERE `email` = ?', [$email]);
        if (empty($result)) {
            $OTP = rand(100000, 999999);
            $this->db('INSERT INTO `password`(`email`, `OTP`) VALUES (?, ?)', [$email, $OTP]);
            return $this->email($email, $OTP);
        } else {
            return ['success' => true, 'message' => 'Email has already been sent, type your OTP and new password to reset it'];
        }
    }

    private function email($email, $OTP)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['EMAIL_LOGIN'];
            $mail->Password = $_ENV['EMAIL_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('otp@tinyconnect.org', $_ENV['APP_NAME']);
            $mail->addAddress($email, $_ENV['APP_NAME'] . ' user');
            $mail->addReplyTo('support@tinyconnect.org', $_ENV['APP_NAME'] . ' Support Team');

            $mail->isHTML(true);
            $mail->Subject = $_ENV['APP_NAME'] . ' One Time Password OTP';
            $mail->Body = '
                <div style="font-family: Arial, sans-serif; line-height: 1.6;">
                    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
                        <div style="text-align: center;">
                            <h1 style="padding: 15px;">' . $_ENV['APP_NAME'] . '</h1>
                        </div>
                        <div style="margin-top: 20px; padding: 15px; background-color: #f5f5f5; border-radius: 5px;">
                            <p>Dear User,</p>
                            <p>Thank you for using ' . $_ENV['APP_NAME'] . '!</p>
                            <p>To ensure the utmost security for your account, we have implemented a dynamic One-Time Password (OTP) system. Please use the following OTP for your password reset</p>
                            <p style="font-size: 24px; font-weight: bold;">Your One-Time Password: ' . $OTP . '</p>
                        </div>
                        <div style="margin-top: 20px;">
                            <p>Here\'s how you can reset your password:</p>
                            <ol>
                            <li>Go to https://tinyconnect.org/password-reset.</li>
                            <li>On the password reset page, enter your registered email address.</li>
                            <li>Provided OTP & New Password.</li>
                            <li>Click on the "Reset" button.</li>
                            </ol>
                            <p>Remember, this One-Time Password will change after 3 reset attempt or successful reset, providing an extra layer of protection for your account.</p>
                            <p>This OTP is valid for next 30 mintues only, any attempt of reset after will result in new OTP to be sent</p>
                        </div>
                        <div style="text-align: center;">
                            <a href="https://tinyconnect.org/password-reset" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #007BFF; color: #ffffff; text-decoration: none; border-radius: 5px;">Login Now</a>
                        </div>
                        <div style="margin-top: 30px; text-align: center;">
                            <p>If you did not attempt to reset or have any concerns about your account\'s security, please contact our support team immediately at:</p>
                            <p>Email: support@tinyconnect.org</p>
                        </div>
                        <div style="text-align: center; margin-top: 20px;">
                            <p>Thank you for using ' . $_ENV['APP_NAME'] . '. We\'re committed to ensuring a safe and enjoyable experience for all our users.</p>
                        </div>
                    </div>
                </div>
            ';
            $mail->AltBody = 'Your OTP is: ' . $OTP;

            $mail->send();
            return ['success' => true, 'message' => 'Email with OTP sent to ' . $email];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'There was an error sending your password'];
        }
    }
}