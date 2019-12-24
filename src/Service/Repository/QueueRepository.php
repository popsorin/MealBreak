<?php

/*
 * Written by Popa Alexandru
 */

namespace Team1\Service\Repository;

use PDO;
use PDOException;
use Team1\Entity\Queuer;
use Team1\Exception\Persistency\ConnectionLostException;
use Team1\Exception\Persistency\DeletionFailedException;
use Team1\Exception\Persistency\QueuerNotFoundException;
use Team1\Exception\Persistency\ReturnAllFailedException;

class QueueRepository
{
    private $connection;

    public function __construct()
    {
        try {
            $host = 'localhost';
            $db = 'MealBreak';
            $username = 'root';
            $password = 'root';
            try {
                $this->connection = new PDO("mysql:host=$host;dbname=$db", $username, $password);
                // set the PDO error mode to exception
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //echo "Connected successfully";
                return $this->connection;
            } catch (PDOException $e) {
//            echo "Connection failed: " . $e->getMessage();
                return FALSE;
            }
        } catch (PDOException $exception) {
            throw new ConnectionLostException();
        }
    }

    function add($account_id)
    {
        $sqlQuery = $this->connection->prepare("SELECT * FROM Queue where (account_id) = ?;");
        $sqlQuery->execute(array($account_id));
        $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
        if(empty($row)) {
            $query = $this->connection->prepare("INSERT INTO Queue (account_id) values (:account_id)");
            $query->bindParam(':account_id', $account_id);
            $query->execute();
        }

    }

    public function getAll(): array
    {
        try {
            $records = array();
            $sqlQuery = $this->connection->prepare("SELECT * FROM Queue;");
            $sqlQuery->execute();
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            while ($row !== false) {
                $queuer = new Queuer();
                $queuer->setId($row['id']);
                $queuer->setAccountId($row['account_id']);
                array_push($records, $queuer);
                $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            }

            return $records;
        } catch (PDOException $exception) {
            throw new ReturnAllFailedException();
        }
    }

    public function delete(int $account_id)
    {
        try {
            $sqlQuery = $this->connection->prepare("SELECT id FROM Queue WHERE account_id = ?;");
            $queryResult = $sqlQuery->execute(array($account_id));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row === false) {
                throw new QueuerNotFoundException();
            }
            $sqlQuery = $this->connection->prepare("DELETE FROM Queue WHERE account_id = ?;");
            $queryResult = $sqlQuery->execute(array($account_id));
        } catch (PDOException $exception) {
            throw new DeletionFailedException();
        }
    }
}