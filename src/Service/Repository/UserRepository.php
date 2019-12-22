<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Service\Repository;

use PDOException;
use Team1\Entity\HasId;
use Team1\Exception\Persistency\ConnectionLostException;
use Team1\Exception\Persistency\DeletionFailedException;
use Team1\Exception\Persistency\EmailAlreadyUsedException;
use Team1\Exception\Persistency\InsertionFailedException;
use Team1\Exception\Persistency\NameAlreadyExistsException;
use Team1\Exception\Persistency\ReturnAllFailedException;
use Team1\Exception\Persistency\SearchForEmailFailed;
use Team1\Exception\Persistency\UpdateFailedException;
use Team1\Exception\Persistency\HasIdNotFoundException;

/**
 * Class UserRepository
 * @package Team1\Service\Repository
 */
class UserRepository implements InterfaceRepository
{
    private $connection;

    public function __construct()
    {
        try {
            $this->connection = new \PDO(
                'mysql:host=localhost;dbname=MealBreak',
                'root',
                '123456789',
                array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
            );
        } catch (PDOException $exception) {
            throw new ConnectionLostException();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function add(HasId $hasId): HasId
    {
        try {
            $name = $hasId->getName();
            $password = $hasId->getPassword();
            $email = $hasId->getEmail();
            $confirm = $hasId->getIsConfirmed();
            $sqlQuery = $this->connection->prepare("SELECT name FROM User WHERE name = ?;");
            $sqlQuery->execute(array($name));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if($row["name"] !== null)
                throw new NameAlreadyExistsException();
            $sqlQuery = $this->connection->prepare("SELECT email FROM User WHERE email = ?;");
            $sqlQuery->execute(array($email));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if($row["email"] !== null)
                throw new EmailAlreadyUsedException();
            $sqlQuery = $this->connection->prepare(
                "INSERT INTO User (name, password, email,confirmed) 
                      VALUES (?, ?, ?,?);"
            );
            $queryResult = $sqlQuery->execute(array($name,$password,$email,$confirm));
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
            $sqlQuery = $this->connection->prepare("SELECT * FROM User;");
            $sqlQuery->execute();
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            while ($row !== false) {
                $hasId = new HasId();
                $hasId->setId($row['id']);
                $hasId->setName($row['name']);
                $hasId->setPassword($row['password']);
                $hasId->setEmail($row['email']);
                $hasId->setConfirmed($row['confirmed']);
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
            $sqlQuery = $this->connection->prepare("SELECT id FROM User WHERE id = ?;");
            $queryResult = $sqlQuery->execute(array($id));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row === false) {
                throw new HasIdNotFoundException();
            }
            $sqlQuery = $this->connection->prepare("DELETE FROM User WHERE id = ?;");
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
            $id = $hasId->getId();
            $confirm = $hasId->getIsConfirmed();
            $sqlQuery = $this->connection->prepare("SELECT id FROM User WHERE id = ?;");
            $queryResult = $sqlQuery->execute(array($id));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row === false) {
                throw new HasIdNotFoundException();
            }
            $sqlQuery = $this->connection->prepare(
                "UPDATE HasId SET (confirmed = ?) 
                         WHERE id = ?;"
            );
            $queryResult = $sqlQuery->execute(array($confirm, $id));

            return $hasId;
        } catch (PDOException $exception) {
            throw new UpdateFailedException();
        }
    }
}
