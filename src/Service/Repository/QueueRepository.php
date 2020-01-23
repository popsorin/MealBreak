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
use Team1\Entity\HasId;

class QueueRepository
{
    private $connection;

    /**
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * @param PDO $connection
     */
    public function setConnection(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function __construct()
    {
        try {
            $host = 'localhost';
            $db = 'MealBreak';
            $username = 'root';
            $password = '123456789';
            try {
                $this->connection = new PDO("mysql:host=$host;dbname=$db", $username, $password);
                // set the PDO error mode to exception
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //echo "Connected successfully";
                return $this->connection;
            } catch (PDOException $e) {
                return FALSE;
            }
        } catch (PDOException $exception) {
            throw new ConnectionLostException();
        }
    }

    public function add(Queuer $queuer) : Queuer
    {
        try {
            $sqlQuery = $this->connection->prepare("SELECT * FROM Queue where (account_id) = ?;");
            $sqlQuery->execute(array($queuer->getAccountId()));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if(empty($row)) {
                $sqlQuery = $this->connection->prepare("INSERT INTO Queue (account_id) values (?);");
                $sqlQuery->execute(array($queuer->getAccountId()));
            }
            return $queuer;
        } catch (PDOException $exception) {
            throw new InsertionFailedException();
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
    public function getAccountsWithAge() {
        try {
            $sqlQuery = $this->connection->prepare("SELECT q.account_id,a.age FROM Queue q INNER JOIN (SELECT id, age FROM Account) a on a.id = q.account_id;");
            $sqlQuery->execute();
            $records = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            return $records;
        } catch (PDOException $exception) {
            throw new ReturnAllFailedException();
        }
    }
    public function delete($queuer_id)
    {
        try {
            $sqlQuery = $this->connection->prepare("SELECT id FROM Queue WHERE account_id = ?;");
            $sqlQuery->execute(array($queuer_id));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row === false) {
                throw new QueuerNotFoundException();
            }
            $sqlQuery = $this->connection->prepare("DELETE FROM Queue WHERE account_id = ?;");
            $sqlQuery->execute(array($queuer_id));
        } catch (PDOException $exception) {
            throw new DeletionFailedException();
        }
    }
}
