<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Service\Repository;

use PDOException;
use Team1\Entity\User;
use Team1\Exception\Persistency\ConnectionLostException;
use Team1\Exception\Persistency\DeletionFailedException;
use Team1\Exception\Persistency\EmailAlreadyUsedException;
use Team1\Exception\Persistency\InsertionFailedException;
use Team1\Exception\Persistency\NameAlreadyExistsException;
use Team1\Exception\Persistency\ReturnAllFailedException;
use Team1\Exception\Persistency\SearchForEmailFailed;
use Team1\Exception\Persistency\UpdateFailedException;
use Team1\Exception\Persistency\UserNotFoundException;

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
    public function add(User $user): User
    {
        try {
            $name = $user->getName();
            $password = $user->getPassword();
            $email = $user->getEmail();
            $confirm = $user->getIsConfirmed();
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
            $user->setId($id);

            return $user;
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
                $user = new User();
                $user->setId($row['id']);
                $user->setName($row['name']);
                $user->setPassword($row['password']);
                $user->setEmail($row['email']);
                $user->setConfirmed($row['confirmed']);
                array_push($records, $user);
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
                throw new UserNotFoundException();
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
    public function update(User $user): User
    {
        try {
            $id = $user->getId();
            $confirm = $user->getIsConfirmed();
            $sqlQuery = $this->connection->prepare("SELECT id FROM User WHERE id = ?;");
            $queryResult = $sqlQuery->execute(array($id));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row === false) {
                throw new UserNotFoundException();
            }
            $sqlQuery = $this->connection->prepare(
                "UPDATE User SET (confirmed = ?) 
                         WHERE id = ?;"
            );
            $queryResult = $sqlQuery->execute(array($confirm, $id));

            return $user;
        } catch (PDOException $exception) {
            throw new UpdateFailedException();
        }
    }

    /**
     * @param User $user
     * @return bool
     * @throws EmailAlreadyUsedException
     *
    public function searchEmail(User $user): boolean
    {
        try{
            $email = $user->getEmail();
            $sqlQuery = $this->connection->prepare("SELECT email FROM User WHERE email = ?");
            $queryResult = $sqlQuery->execute(array($email));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if($row !== false)
                throw new EmailAlreadyUsedException();

            return false;
        } catch (PDOException $exception) {
            throw new SearchForEmailFailed();
        }
    }*/
}
