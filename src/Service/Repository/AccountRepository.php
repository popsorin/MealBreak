<?php
/*
 * Written by Andrei
 */

namespace Team1\Service\Repository;

use PDOException;
use Team1\Entity\Account;
use Team1\Entity\HasId;
use Team1\Exception\Persistency\AccountNotFoundException;
use Team1\Exception\Persistency\AlreadyOnlineException;
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

class AccountRepository extends UserRepository
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
    public function add(HasId $account): HasId
    {
        try {
            $name = $account->getName();
            $password = $account->getPassword();
            $email = $account->getEmail();
            $confirm = $account->getIsConfirmed();
            $sqlQuery = $this->connection->prepare("SELECT email FROM Account WHERE email = ?;");
            $sqlQuery->execute(array($email));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row["email"] !== null) {
                throw new EmailAlreadyUsedException();
            }
            $sqlQuery = $this->connection->prepare("SELECT name FROM Account WHERE name = ?;");
            $sqlQuery->execute(array($name));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row["username"] !== null) {
                throw new NameAlreadyExistsException();
            }
            $query = $this->connection->prepare(
                "INSERT INTO Account 
            (
            name, password, email, is_confirmed, description, nickname, isOnline, queueStartTime, age) 
            values (?, ?, ?, ?, '', '', 0, ?, ?);"
            );
            $query->execute(array(
            $account->getName(), $account->getPassword(), $account->getEmail(), "true" , $account->getQueueStartTime(), $account->getAge()));

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
                $hasId->setAge($row['age']);
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
            /*
            if ($row['isOnline'] === "1") {
                throw new AlreadyOnlineException();
            }*/
            $sqlQuery = $this->connection->prepare("UPDATE Account SET isOnline = ? WHERE email = ?");
            $sqlQuery->execute(array($account->getIsOnline(), $account->getEmail()));
            $result = new Account();
            $result->setName($row['name']);
            $result->setEmail($row['email']);
            $result->setPassword($row['password']);
            $result->setId($row['id']);
            $result->setIsOnline($account->getIsOnline());

            return $result;
        } catch (PDOException $exception) {
            throw new SearchAccountFailedException();
        }
    }

    /**
     * @param int $id
     * @return HasId
     * @throws AccountNotFoundException
     * @throws SearchAccountFailedException
     */
    public function searchById(int $id): HasId
    {
        try {
            $sqlQuery = $this->connection->prepare("SELECT * FROM Account WHERE id = ?;");
            $sqlQuery->execute(array($id));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row === false) {
                throw new AccountNotFoundException();
            }
            /*
            if ($row['isOnline'] === "1") {
                throw new AlreadyOnlineException();
            }*/
            $result = new Account();
            $result->setName($row['name']);
            $result->setEmail($row['email']);
            $result->setDescription($row['description']);
            $result->setAge($row['age']);
            $result->setPassword($row['password']);
            $result->setIsConfirmed($row['is_confirmed']);
            $result->setNickname($row['nickname']);
            $result->setIsOnline($row['isOnline']);
            $result->setQueueStartTime($row['queueStartTime']);
            $result->setId($row['id']);

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
            $queryResult = $sqlQuery->execute(array($hasId->getId()));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row === false) {
                throw new AccountNotFoundException();
            }
            $sqlQuery = $this->connection->prepare(
                "UPDATE Account SET  description = ?, email = ?, name = ?, nickname = ?, age = ?, isOnline = ?
                         WHERE id = ?;"
            );
            $queryResult = $sqlQuery->execute(array(
                $hasId->getDescription(),
                $hasId->getEmail(),
                $hasId->getName(),
                $hasId->getNickname(),
                $hasId->getAge(),
                $hasId->getIsOnline(),
                $hasId->getId()));

            return $hasId;
        } catch (PDOException $exception) {
            throw new UpdateFailedException();
        }
    }

    /**
     * @param int $id
     * @return HasId
     * @throws AccountNotFoundException
     * @throws UpdateFailedException
     */
    public function logOut(int $id): HasId
    {
        try {
            $sqlQuery = $this->connection->prepare("SELECT id FROM Account WHERE id = ?;");
            $queryResult = $sqlQuery->execute(array($id));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row === false) {
                throw new AccountNotFoundException();
            }
            $sqlQuery = $this->connection->prepare(
                "UPDATE Account SET (isOnline = 0)
                         WHERE id = ?;"
            );
            $queryResult = $sqlQuery->execute(array(
                $hasId->getId()));

            return $hasId;
        } catch (PDOException $exception) {
            throw new UpdateFailedException();
        }
    }
}
