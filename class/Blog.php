<?php
namespace Blog;

use Model\Dbh;

class Blog extends Dbh
{

    public function create(array $data)
    {
        // Implement your create logic here
        $this->db('INSERT INTO `blog`(`uploaderID`, `title`, `body`) VALUES (?, ?, ?)', $data);
    }

    public function read(int $id)
    {
        // Implement your read logic here
        $array = [];
        $array['blog'] = $this->fetch('SELECT * FROM `blog` WHERE `blogID` = ?', [$id]);
        $uploader = $this->fetch('SELECT `name` FROM `user` WHERE `userID` = ?', [$array['blog']['uploaderID']]);
        $array['uploaderName'] = $uploader['name'];
        $comments = $this->fetchAll('SELECT * FROM `blog-comment` WHERE `blogID` = ? ORDER BY `commentID` DESC', [$id]);
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
        $blogDetail = $this->fetch('SELECT * FROM `blog` WHERE `blogID` = ?', [$id]);
        if ($blogDetail['uploaderID'] == $_SESSION['userID']) {
            $this->db('DELETE FROM `blog` WHERE `blogID` = ?', [$id]);
            return true;
        }
        return false;

    }

    public function getAll()
    {
        // Implement your get all logic here
    }

}