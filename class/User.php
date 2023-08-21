<?php
namespace User;

use Model\Dbh;

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
}