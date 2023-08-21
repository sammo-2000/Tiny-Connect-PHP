<?php
namespace Chat;

use Model\Dbh;

class Chat extends Dbh
{

    public function create(int $userID, string $chat)
    {
        $currentUser = $_SESSION['userID'];

        // If old chat exist 
        $query = 'SELECT * FROM `chat-recent` WHERE (`senderID` = ? AND `receiverID` = ?) OR (`senderID` = ? AND `receiverID` = ?)';
        $result = $this->fetch($query, [$userID, $currentUser, $currentUser, $userID]);
        if (empty($result)) {
            $this->db('INSERT INTO `chat-recent`(`senderID`, `receiverID`) VALUES (?, ?)', [$currentUser, $userID]);
        } else {
            $this->db('UPDATE `chat-recent` SET `date` = NOW() WHERE `recentID` = ?', [$result['recentID']]);
        }
        // Create new chat
        $this->db('INSERT INTO `chat`(`senderID`, `receiverID`, `chat`) VALUES (?, ?, ?)', [$currentUser, $userID, $chat]);
    }

    public function read(int $id)
    {
        // Implement your read logic here
        $query = 'SELECT * FROM `chat` WHERE (`senderID` = ? AND `receiverID` = ?) OR (`senderID` = ? AND `receiverID` = ?) LIMIT 500';
        $result = $this->fetchAll($query, [$id, $_SESSION['userID'], $_SESSION['userID'], $id]);
        if (empty($result))
        {
            return false;
        }
        return $result;
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