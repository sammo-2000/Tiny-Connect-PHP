<?php
namespace User;

use Model\Dbh;

class User extends Dbh
{

    public function create(array $data)
    {
        // Implement your create logic here
        $this->db('INSERT INTO `user`(`email`, `password`) VALUES (?, ?)', $data);
    }

    public function read(int $id)
    {
        return $this->fetch('SELECT * FROM `users` WHERE `userID` = ?', [$id]);
    }

    public function update(int $id, array $data)
    {
        $data[] = $id;
        $this->db('UPDATE `users` SET `displayName`= ?, `profile-image`= ?, `bio`= ? WHERE `userID` = ?', []);
    }

    public function delete(int $id)
    {
        // Implement your delete logic here
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

}