<?php
namespace Follow;

use Model\Dbh;

class Follow extends Dbh
{
    public function follow(int $userID)
    {
        $result = $this->fetch('SELECT * FROM `follow` WHERE `followID` = ? AND `followingID` = ?', [$_SESSION['userID'], $userID]);

        if (empty($result)) {
            $this->db('INSERT INTO `follow`(`followID`, `followingID`) VALUES (?, ?)', [$_SESSION['userID'], $userID]);
            return [
                'action' => 'followed'
            ];
        }
        $this->db('DELETE FROM `follow` WHERE `followID` = ? AND `followingID` = ?', [$_SESSION['userID'], $userID]);
        return [
            'action' => 'unfollowed'
        ];
    }
}