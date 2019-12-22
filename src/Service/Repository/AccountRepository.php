<?php
/*
 * Written by Andrei
 */

namespace Team1\Service\Repository;

use PDOException;
use Team1\Entity\Account;
use Team1\Entity\HasId;
use Team1\Exception\Persistency\AccountNotFoundException;
use Team1\Exception\Persistency\ConnectionLostException;
use Team1\Exception\Persistency\DeletionFailedException;
use Team1\Exception\Persistency\EmailAlreadyUsedException;
use Team1\Exception\Persistency\InsertionFailedException;
use Team1\Exception\Persistency\NameAlreadyExistsException;
use Team1\Exception\Persistency\ReturnAllFailedException;
use Team1\Exception\Persistency\SearchAccountFailedException;
use Team1\Exception\Persistency\UpdateFailedException;

/**
 * Class AccountRepository
 * @package Team1\Service\Repository
 */

class AccountRepository implements InterfaceRepository
{
    private $connection;

    /**
     * AccountRepository constructor.
     * @throws ConnectionLostException
     */
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
    public function add(HasId $account): HasId
    {
        try {
            $name = $account->getName();
            $password = $account->getPassword();
            $email = $account->getEmail();
            $confirm = $account->getIsConfirmed();
            $sqlQuery = $this->connection->prepare("SELECT name FROM Account WHERE name = ?;");
            $sqlQuery->execute(array($name));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row["name"] !== null) {
                throw new NameAlreadyExistsException();
            }

            $sqlQuery = $this->connection->prepare("SELECT email FROM Account WHERE email = ?;");
            $sqlQuery->execute(array($email));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row["email"] !== null) {
                throw new EmailAlreadyUsedException();
            }

            $sqlQuery = $this->connection->prepare(
                "INSERT INTO Account (name, password, email)
                      VALUES (?, ?, ?);"
            );
            $queryResult = $sqlQuery->execute(array($name,$password,$email,$confirm));
            $id = $this->connection->lastInsertId();
            $account->setId($id);

            return $account;
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
            $sqlQuery = $this->connection->prepare("SELECT * FROM Account;");
            $sqlQuery->execute();
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            while ($row !== false) {
                $hasId = new Account();
                $hasId->setId($row['id']);
                $hasId->setName($row['name']);
                $hasId->setPassword($row['password']);
                $hasId->setEmail($row['email']);
                $hasId->setDescription($row['description']);
                $hasId->setNickname($row['nickname']);
                array_push($records, $hasId);
                $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
                $_SESSION['id'] = $row['id'];
            }
            return $records;
        } catch (PDOException $exception) {
            throw new ReturnAllFailedException();
        }
    }

    /**
     * @param HasId $account
     * @return HasId
     * @throws AccountNotFoundException
     * @throws SearchAccountFailedException
     */
    public function search(HasId $account): HasId
    {
        try {
            $sqlQuery = $this->connection->prepare("SELECT * FROM Account WHERE email = ? AND password = ?;");
            $sqlQuery->execute(array($account->getEmail(), $account->getPassword()));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row === false) {
                throw new AccountNotFoundException();
            }
            $result = new Account();
            $result->setName($row['name']);
            $result->setEmail($row['email']);
            $result->setDesctiption($row['description']);
            $result->setPassword($row['password']);
            $result->setId($row['id']);
            $result->setNickname($row['nickname']);

            return $result;
        } catch (PDOException $exception) {
            throw new SearchAccountFailedException();
        }
    }


    /**
     * {@inheritDoc}
     */
    public function delete(int $id)
    {
        try {
            $sqlQuery = $this->connection->prepare("SELECT id FROM Account WHERE id = ?;");
            $queryResult = $sqlQuery->execute(array($id));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row === false) {
                throw new AccountNotFoundException();
            }
            $sqlQuery = $this->connection->prepare("DELETE FROM HasId WHERE id = ?;");
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
            $sqlQuery = $this->connection->prepare("SELECT id FROM Account WHERE id = ?;");
            $queryResult = $sqlQuery->execute(array($id));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row === false) {
                throw new AccountNotFoundException();
            }
            $sqlQuery = $this->connection->prepare(
                "UPDATE Account SET (password = ?), (description = ?), (email = ?)
                         WHERE id = ?;"
            );
            $queryResult = $sqlQuery->execute(array(
                $hasId->getPassword(),
                $hasId->getDescription(),
                $hasId->getEmail(),
                $hasId->getId()));

            return $hasId;
        } catch (PDOException $exception) {
            throw new UpdateFailedException();
        }
    }
}
