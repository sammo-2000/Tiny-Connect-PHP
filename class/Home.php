<?php
namespace Home;

use Model\Dbh;

class Home extends Dbh
{
    public function PublicPosts(int $limit)
    {
        $results = $this->fetchAll("SELECT * FROM `post` LIMIT $limit", []);
        $array = [];

        foreach ($results as $result) {
            $arrayNew = [];

            $arrayNew['user'] = $this->fetch('SELECT `userID`, `name`, `image` FROM `user` WHERE `userID` = ?', [$result['uploaderID']]);
            $arrayNew['post'] = $result;

            $array['postInfo'][] = $arrayNew;
        }

        // Check if we have more posts to show
        if (count($results) >= $limit) {
            $array['morePost'] = true;
        } else {
            $array['morePost'] = false;
        }

        // Set new limit
        $array['limit'] = $limit + 8;

        return $array;
    }

    public function PublicBlogs(int $limit)
    {
        $results = $this->fetchAll("SELECT * FROM `blog` LIMIT $limit", []);
        $array = [];

        foreach ($results as $result) {
            $arrayNew = [];

            $arrayNew['user'] = $this->fetch('SELECT `userID`, `name`, `image` FROM `user` WHERE `userID` = ?', [$result['uploaderID']]);
            $arrayNew['blog'] = $result;

            $array['blogInfo'][] = $arrayNew;
        }

        // Check if we have more posts to show
        if (count($results) >= $limit) {
            $array['moreBlog'] = true;
        } else {
            $array['moreBlog'] = false;
        }

        // Set new limit
        $array['limit'] = $limit + 8;

        return $array;
    }

    public function PrivatePosts(int $limit)
    {
        $followers = $this->fetchAll('SELECT * FROM `follow` WHERE `followID` = ?', [$_SESSION['userID']]);

        $followersIDs = [];

        foreach ($followers as $follower) {
            $followersIDs[] = $follower['followingID'];
        }

        $followersIDs[] = $_SESSION['userID'];

        $followerIDsList = implode(',', $followersIDs);

        $results = $this->fetchAll("SELECT * FROM `post` WHERE `uploaderID` IN ($followerIDsList) LIMIT $limit", []);
        $array = [];

        foreach ($results as $result) {
            $arrayNew = [];

            $arrayNew['user'] = $this->fetch('SELECT `userID`, `name`, `image` FROM `user` WHERE `userID` = ?', [$result['uploaderID']]);
            $arrayNew['post'] = $result;

            $array['postInfo'][] = $arrayNew;
        }

        // Check if we have more posts to show
        if (count($results) >= $limit) {
            $array['morePost'] = true;
        } else {
            $array['morePost'] = false;
        }

        // Set new limit
        $array['limit'] = $limit + 8;

        return $array;
    }

    public function PrivateBlogs(int $limit)
    {
        $followers = $this->fetchAll('SELECT * FROM `follow` WHERE `followID` = ?', [$_SESSION['userID']]);

        $followersIDs = [];

        foreach ($followers as $follower) {
            $followersIDs[] = $follower['followingID'];
        }

        $followersIDs[] = $_SESSION['userID'];

        $followerIDsList = implode(',', $followersIDs);

        $results = $this->fetchAll("SELECT * FROM `blog` WHERE `uploaderID` IN ($followerIDsList) LIMIT $limit", []);
        $array = [];

        foreach ($results as $result) {
            $arrayNew = [];

            $arrayNew['user'] = $this->fetch('SELECT `userID`, `name`, `image` FROM `user` WHERE `userID` = ?', [$result['uploaderID']]);
            $arrayNew['blog'] = $result;

            $array['blogInfo'][] = $arrayNew;
        }

        // Check if we have more posts to show
        if (count($results) >= $limit) {
            $array['moreBlog'] = true;
        } else {
            $array['moreBlog'] = false;
        }

        // Set new limit
        $array['limit'] = $limit + 8;

        return $array;
    }
}