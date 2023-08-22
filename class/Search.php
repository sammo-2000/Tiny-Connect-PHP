<?php
namespace Search;

use Model\Dbh;

class Search extends Dbh
{
    public function getAll($search = null)
    {
        // Implement your get all logic here
        if ($search === null) {
            $query = 'SELECT `userID`, `name`, `image` FROM `user` WHERE `name` IS NOT NULL AND `userID` != ? ORDER BY `userID` DESC LIMIT 40';
            return $this->fetchAll($query, [$_SESSION['userID']]);
        }
        $query = 'SELECT `userID`, `name`, `image` FROM `user` WHERE `name` IS NOT NULL AND `userID` != ? AND `name` LIKE ? ORDER BY `userID` DESC LIMIT 40';
        $search = '%' . $search . '%';
        return $this->fetchAll($query, [$_SESSION['userID'], $search]);
    }
}