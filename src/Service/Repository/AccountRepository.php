<?php
/*
 * Written by Andrei
 */

namespace Team1\Service\Repository;

use PDOException;
use Team1\Entity\Account;
use Team1\Entity\User;
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
                'root',
                array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
            );
        } catch (PDOException $exception) {
            throw new ConnectionLostException();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function add(User $account): User
    {
        try {
            $id = $account->getId();
            $name = $account->getName();
            $password = $account->getPassword();
            $email = $account->getEmail();

            $sqlQuery = $this->connection->prepare("SELECT username FROM Accounts WHERE username = ?;");
            $sqlQuery->execute(array($name));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row["username"] !== null)
                throw new NameAlreadyExistsException();

            $sqlQuery = $this->connection->prepare("SELECT email FROM Accounts WHERE email = ?;");
            $sqlQuery->execute(array($email));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row["email"] !== null)
                throw new EmailAlreadyUsedException();

            $query = $this->connection->prepare("INSERT INTO Accounts (username, email, password, first_name, last_name, is_online, queue_start_time, description) values (:username, :email, :password, '', '', 0, CURRENT_TIMESTAMP, '')");
            $query->bindParam(":username", $name);
            $query->bindParam(":email", $email);
            $query->bindParam(":password", $password);

            $query->execute();

            $sqlQuery = $this->connection->prepare("DELETE FROM Users WHERE id = ?;");
            $sqlQuery->execute(array($id));

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
                $user = new Account();
                $user->setId($row['id']);
                $user->setName($row['username']);
                $user->setPassword($row['password']);
                $user->setEmail($row['email']);
                $user->setDescription($row['description']);
                $user->setLastName($row['last_name']);
                $user->setLastName($row['first_name']);
                $user->setIsOnline($row['is_online']);
                $user->setQueuedStartTime($row['queue_start_time']);
                array_push($records, $user);
                $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            }
            return $records;
        } catch (PDOException $exception) {
            throw new ReturnAllFailedException();
        }
    }

    public function search(User $account)
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
            $result->setDescription($row['description']);
            $result->setPassword($row['password']);


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
            $sqlQuery = $this->connection->prepare("DELETE FROM User WHERE id = ?;");
            $queryResult = $sqlQuery->execute(array($id));
        } catch (PDOException $exception) {
            throw new DeletionFailedException();
        }
    }

    /**
     * @param User $user
     * @return User
     * @throws AccountNotFoundException
     * @throws UpdateFailedException
     */
    public function update(User $user): User
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
                $user->getPassword(),
                $user->getDescription(),
                $user->getEmail(), $user->getId()));

            return $user;
        } catch (PDOException $exception) {
            throw new UpdateFailedException();
        }
    }
}
