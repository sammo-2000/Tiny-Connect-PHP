<?php
namespace Blog;

use Model\Dbh;

class Comment extends Dbh
{

    public function create(array $data)
    {
        // Implement your create logic here
        $this->db('INSERT INTO `blog-comment`(`userID`, `blogID`, `comment`) VALUES (?, ?, ?)', $data);
    }

    public function read(int $id)
    {
        // Implement your read logic here
    }

    public function update(int $id, array $data)
    {
        // Implement your update logic here
    }

    public function delete(int $id)
    {
        // Implement your delete logic here
        $result = $this->fetch('SELECT * FROM `blog-comment` WHERE `commentID` = ?', [$id]);
        if ($result['userID'] === $_SESSION['userID']) {
            $this->db('DELETE FROM `blog-comment` WHERE `commentID` = ?', [$id]);
            return true;
        }
        return false;
    }

    public function getAll()
    {
        // Implement your get all logic here
    }

}