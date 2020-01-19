<?php

namespace Team1\Service\Repository;

use Team1\Entity\Account;
use Team1\Entity\HasId;
use Team1\Entity\Pub;
use Team1\Exception\Persistency\AccountNotFoundException;
use Team1\Exception\Persistency\ConnectionLostException;
use Team1\Exception\Persistency\DeletionFailedException;
use Team1\Exception\Persistency\InsertionFailedException;
use Team1\Exception\Persistency\ReturnAllFailedException;
use Team1\Exception\Persistency\UpdateFailedException;

/**
 * Class PubRepository
 * @package Team1\Service\Repository
 */
class PubRepository implements InterfaceRepository
{
    public function __construct()
    {
        try {
            $host = 'localhost';
            $db = 'MealBreak';
            $username = 'root';
            //alex
            //$password = 'root';
            $password = '123456789';
            $this->connection = new \PDO("mysql:host=$host;dbname=$db", $username, $password);
            // set the PDO error mode to exception
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $exception) {
            throw new ConnectionLostException();
        }
    }


    /**
     * @param HasId $hasId
     * @return HasId
     * @throws InsertionFailedException
     */
    public function add(HasId $hasId): HasId
    {
        try {
            $name = $hasId->getName();
            $location = $hasId->getLocation();
            $sqlQuery = $this->connection->prepare(
                "INSERT INTO Pub (name, location) 
                      VALUES (?, ?);"
            );
            $queryResult = $sqlQuery->execute(array($name,$location));
            $id = $this->connection->lastInsertId();
            $hasId->setId($id);

            return $hasId;
        } catch (PDOException $exception) {
            throw new InsertionFailedException();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getAll(): array
    {
        try {
            $records = array();
            $sqlQuery = $this->connection->prepare("SELECT * FROM Pub;");
            $sqlQuery->execute();
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            while ($row !== false) {
                $hasId = new Pub();
                $hasId->setId($row['id']);
                $hasId->setName($row['name']);
                $hasId->setLocation($row['location']);
                array_push($records, $hasId);
                $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            }
            return $records;
        } catch (PDOException $exception) {
            throw new ReturnAllFailedException();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function delete(int $id)
    {
        try {
            $sqlQuery = $this->connection->prepare("SELECT id FROM Pub WHERE id = ?;");
            $queryResult = $sqlQuery->execute(array($id));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row === false) {
                throw new AccountNotFoundException();
            }
            $sqlQuery = $this->connection->prepare("DELETE FROM Pub WHERE id = ?;");
            $queryResult = $sqlQuery->execute(array($id));
        } catch (PDOException $exception) {
            throw new DeletionFailedException();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function update(HasId $hasId): HasId
    {
        try {
            $sqlQuery = $this->connection->prepare("SELECT id FROM Pub WHERE id = ?;");
            $queryResult = $sqlQuery->execute(array($hasId->getId()));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row === false) {
                throw new AccountNotFoundException();
            }
            $sqlQuery = $this->connection->prepare(
                "UPDATE Pub SET name = ?, location = ? WHERE id = ?;"
            );
            $queryResult = $sqlQuery->execute(array(
                $hasId->getName(),
                $hasId->getLocation(),
                $hasId->getId()
            ));

            return $hasId;
        } catch (PDOException $exception) {
            throw new UpdateFailedException();
        }
    }
}
