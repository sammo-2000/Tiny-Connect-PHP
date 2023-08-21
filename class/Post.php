<?php
namespace Post;

use Model\Dbh;

class Post extends Dbh
{

    public function create(array $data)
    {
        // Implement your create logic here
        $this->db('INSERT INTO `post`(`uploaderID`, `caption`, `image`) VALUES (?, ?, ?)', $data);
    }

    public function read(int $id)
    {
        // Implement your read logic here
        $array = [];
        $array['post'] = $this->fetch('SELECT * FROM `post` WHERE `postID` = ?', [$id]);
        $uploader = $this->fetch('SELECT `name` FROM `user` WHERE `userID` = ?', [$array['post']['uploaderID']]);
        $array['uploaderName'] = $uploader['name'];
        $comments = $this->fetchAll('SELECT * FROM `post-comment` WHERE `postID` = ? ORDER BY `commentID` DESC', [$id]);
        if (!empty($comments)) {
            $newArray = [];
            foreach ($comments as $comment) {
                $user = $this->fetch('SELECT `name`, `image` FROM `user` WHERE `userID` = ?', [$comment['userID']]);
                $newArray[] = [
                    'comment' => $comment,
                    'user' => $user
                ];  
            }
            $array['comment'] = $newArray;
        }
        return $array;
    }

    public function update(int $id, array $data)
    {
        // Implement your update logic here
    }

    public function delete(int $id)
    {
        // Implement your delete logic here
    }

    public function getAll()
    {
        // Implement your get all logic here
    }

}