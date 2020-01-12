<?php

/*
 * Written by Pop Sorin & Popa Alexandru
 */

namespace Team1\Service\Repository;

use PDO;
use PDOException;
use Team1\Entity\HasId;
use Team1\Entity\User;
use Team1\Exception\Persistency\ConnectionLostException;
use Team1\Exception\Persistency\DeletionFailedException;
use Team1\Exception\Persistency\EmailAlreadyUsedException;
use Team1\Exception\Persistency\InsertionFailedException;
use Team1\Exception\Persistency\NameAlreadyExistsException;
use Team1\Exception\Persistency\ReturnAllFailedException;
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
            $host = 'localhost';
            $db = 'MealBreak';
            $username = 'root';
            $password = 'root';
            $this->connection = new PDO("mysql:host=$host;dbname=$db", $username, $password);
                // set the PDO error mode to exception
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
            $sqlQuery = $this->connection->prepare("SELECT email FROM User WHERE email = ?;");
            $sqlQuery->execute(array($email));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row["email"] !== null)
                throw new EmailAlreadyUsedException();
            $token = $this->random_strings(16);
            $now = date("Y-m-d H:i:s");
            $query = $this->connection->prepare("INSERT INTO User (name, password,email,is_confirmed,token,date) values (?, ?, ?, ?, ?, ?)");
            $query->execute(array($name, $password, $email, $confirm, $token, $now));
            $this->send_token($email, $token);
            $id = $this->connection->lastInsertId();
            $hasId->setId($id);

            return $hasId;
        } catch (PDOException $exception) {
            throw new InsertionFailedException();
        }
    }

    //takes a variable and transforms it into a 16 characters
    public function random_strings($length_of_string)
    {
        return substr(bin2hex(random_bytes($length_of_string)), 0, $length_of_string);
    }

    /**
     * @param $email
     * @param $token
     */
    //sends an confiramtion email to the user
    public function send_token($email, $token)
    {
        $to = $email;
        $subject = "Activating your Account";
        $body = "Hello, in order to activate your account please visit https://".$_SERVER['HTTP_HOST'] . "/login?token=" . $token . "\n This code is valid for 2 hours";
        mail($to, $subject, $body);
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
    public function update(HasId $hasId): HasId
    {
        try {
            $id = $hasId->getId();
            $confirm = $hasId->getIsConfirmed();
            $sqlQuery = $this->connection->prepare("SELECT id FROM User WHERE id = ?;");
            $queryResult = $sqlQuery->execute(array($id));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row === false) {
                throw new UserNotFoundException();
            }
            $sqlQuery = $this->connection->prepare("UPDATE User SET (confirmed = ?) WHERE id = ?;");
            $queryResult = $sqlQuery->execute(array($confirm, $id));

            return $hasId;
        } catch (PDOException $exception) {
            throw new UpdateFailedException();
        }
    }

    public function get_user_from_token(string $token)
    {
        $query = $this->connection->prepare('SELECT * FROM User WHERE token = ?');
        $query->execute(array($token));
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getIdFromMail(string $email) {
        $query = $this->connection->prepare('SELECT id FROM Account WHERE email = ?');
        $query->execute($email);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['id'];
    }

    public function getIsConfirmed($username)
    {
        $query = $this->connection->prepare('SELECT is_confirmed FROM User WHERE name = ?');
        $query->execute(array($username));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['is_confirmed'];
    }

    public function getDate($username)
    {
        $query = $this->connection->prepare('SELECT date FROM User WHERE name=?');
        $query->execute(array($username));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $date = $result['date'];
        return $date;
    }

    public function setIsConfirmed($username)
    {
        $query = $this->connection->prepare('UPDATE User SET is_confirmed = 1 WHERE name=?');
        $query->execute(array($username));
    }


    /**
     * @param User $user
     * @return bool
     * @throws EmailAlreadyUsedException
     *
     * public function searchEmail(User $user): boolean
     * {
     * try{
     * $email = $user->getEmail();
     * $sqlQuery = $this->connection->prepare("SELECT email FROM User WHERE email = ?");
     * $queryResult = $sqlQuery->execute(array($email));
     * $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
     * if($row !== false)
     * throw new EmailAlreadyUsedException();
     *
     * return false;
     * } catch (PDOException $exception) {
     * throw new SearchForEmailFailed();
     * }
     * }*/
}
