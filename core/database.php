<?php
namespace model;

use \PDO;

class Dbh
{
    protected function connect()
    {
        $dns = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'];
        $pdo = new PDO($dns, $_ENV['DB_USER'], $_ENV['DB_PASS']);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }

    protected function fetch($sql, $array)
    {
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($array);
        return $stmt->fetch();
    }

    protected function fetchAll($sql, $array)
    {
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($array);
        return $stmt->fetchAll();
    }

    protected function db($sql, $array)
    {
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($array);
    }
}