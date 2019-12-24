<?php

/*
 * Written by Pop Sorin & Popa Alexandru
 */

namespace Team1\Service\Repository;

use PDO;
use PDOException;
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

    /**
     * {@inheritDoc}
     */
    public function add(User $user): User
    {
        try {
            $name = $user->getName();
            $password = $user->getPassword();
            $email = $user->getEmail();
            $sqlQuery = $this->connection->prepare("SELECT username FROM Users WHERE username = ?;");
            $sqlQuery->execute(array($name));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row["username"] !== null)
                throw new NameAlreadyExistsException();
            $sqlQuery = $this->connection->prepare("SELECT email FROM Users WHERE email = ?;");
            $sqlQuery->execute(array($email));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row["email"] !== null)
                throw new EmailAlreadyUsedException();
            $token = $this->random_strings(16);
            $now = date("Y-m-d H:i:s");
            $query = $this->connection->prepare("INSERT INTO Users (username, email, password,is_confirmed,token,date) values (:username,:email,:password,0,:token,:now)");
            $query->bindParam(":username", $name);
            $query->bindParam(":email", $email);
            $query->bindParam(":password", $password);
            $query->bindParam(":token", $token);
            $query->bindParam(":now", $now);

            $query->execute();
            $this->send_token($email, $token);
            $id = $this->connection->lastInsertId();
            //$user->setId($id);

            return $user;
        } catch (PDOException $exception) {
            throw new InsertionFailedException();
        }
    }

    public function random_strings($length_of_string)
    {
        return substr(bin2hex(random_bytes($length_of_string)), 0, $length_of_string);
    }

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
            $sqlQuery = $this->connection->prepare("SELECT id FROM Users WHERE id = ?;");
            $queryResult = $sqlQuery->execute(array($id));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row === false) {
                throw new UserNotFoundException();
            }
            $sqlQuery = $this->connection->prepare("DELETE FROM Users WHERE id = ?;");
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
            $sqlQuery = $this->connection->prepare("SELECT id FROM Users WHERE id = ?;");
            $queryResult = $sqlQuery->execute(array($id));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row === false) {
                throw new UserNotFoundException();
            }
            $sqlQuery = $this->connection->prepare(
                "UPDATE Users SET (confirmed = ?) 
                         WHERE id = ?;"
            );
            $queryResult = $sqlQuery->execute(array($confirm, $id));

            return $user;
        } catch (PDOException $exception) {
            throw new UpdateFailedException();
        }
    }

    public function get_user_from_token($token)
    {
        $query = $this->connection->prepare('SELECT * FROM Users WHERE token=:token');
        $query->bindParam(':token', $token);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getIdFromMail($email) {
        $query = $this->connection->prepare('SELECT id FROM Accounts WHERE email=:email');
        $query->bindParam(':email', $email);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['id'];
    }

    public function getIsConfirmed($username)
    {
        $query = $this->connection->prepare('SELECT is_confirmed FROM Users WHERE username=:username');
        $query->bindParam(':username', $username);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['is_confirmed'];
    }

    public function getDate($username)
    {
        $query = $this->connection->prepare('SELECT date FROM Users WHERE username=:username');
        $query->bindParam(':username', $username);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['date'];
    }

    public function setIsConfirmed($username)
    {
        $query = $this->connection->prepare('UPDATE Users SET is_confirmed = 1 WHERE username=:username');
        $query->bindParam(':username', $username);
        $query->execute();
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
