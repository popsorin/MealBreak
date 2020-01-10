<?php
/*
 * Written by Pop Sorin
 */
namespace Team1\Service\Repository;

use Team1\Entity\Account;
use Team1\Entity\Chatter;
use Team1\Entity\HasId;
use Team1\Entity\User;
use Team1\Exception\Persistency\AccountNotFoundException;
use Team1\Exception\Persistency\ConnectionLostException;
use Team1\Exception\Persistency\DeletionFailedException;
use Team1\Exception\Persistency\EmailAlreadyUsedException;
use Team1\Exception\Persistency\GetMessagesException;
use Team1\Exception\Persistency\InsertionFailedException;
use Team1\Exception\Persistency\NameAlreadyExistsException;
use Team1\Exception\Persistency\PartnerNotFoundException;
use Team1\Exception\Persistency\ReturnAllFailedException;
use Team1\Exception\Persistency\UpdateFailedException;

/**
 * Class ChatterRepository
 * @package Team1\Service\Repository
 */
class ChatterRepository implements InterfaceRepository
{
    private $connection;

    /**
     * ChatterRepository constructor.
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

    public function add(HasId $hasId): HasId
    {
        try {
            $name = $hasId->getName();
            $message = $hasId->getMessage();
            $date = $hasId->getDate();
            $idAccount = $hasId->getIdAccount();
            $idPartener = $hasId->getIdPartener();
            $sqlQuery = $this->connection->prepare(
                "INSERT INTO Chatter (name, message, date, idAccount, idPartener) 
                      VALUES (?, ?, ?, ?, ?);"
            );
            $queryResult = $sqlQuery->execute(array($name,$message,$date,$idAccount,$idPartener));
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
            $sqlQuery = $this->connection->prepare("SELECT * FROM Chatter;");
            $sqlQuery->execute();
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            while ($row !== false) {
                $hasId = new Account();
                $hasId->setId($row['id']);
                $hasId->setName($row['name']);
                $hasId->setMessage($row['password']);
                $hasId->setDate($row['email']);
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
            $sqlQuery = $this->connection->prepare("SELECT id FROM Chatter WHERE id = ?;");
            $queryResult = $sqlQuery->execute(array($id));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row === false) {
                throw new AccountNotFoundException();
            }
            $sqlQuery = $this->connection->prepare("DELETE FROM Chatter WHERE id = ?;");
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
            $sqlQuery = $this->connection->prepare("SELECT id FROM Chatter WHERE id = ?;");
            $queryResult = $sqlQuery->execute(array($hasId->getId()));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row === false) {
                throw new AccountNotFoundException();
            }
            $sqlQuery = $this->connection->prepare(
                "UPDATE Chatter SET message = ?, date = ? WHERE id = ?;"
            );
            $queryResult = $sqlQuery->execute(array(
                $hasId->getMessage(),
                $hasId->getDate(),
                $hasId->getId()
            ));

            return $hasId;
        } catch (PDOException $exception) {
            throw new UpdateFailedException();
        }
    }

    /**
     * @param $idAccount
     * @return HasId
     * @throws AccountNotFoundException
     */
    public function searchPartner($idAccount): HasId
    {

        try {
            $sqlQuery = $this->connection->prepare("SELECT * FROM Chatter WHERE idPartener = ?;");
            $queryResult = $sqlQuery->execute(array($idAccount));
            $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
            if ($row === false) {
                throw new AccountNotFoundException();
            }
            $chatter = new Chatter();
            $chatter->setId($row['id']);
            $chatter->setName($row['name']);
            $chatter->setMessage($row['message']);
            $chatter->setDate($row['date']);
            $chatter->setIdAccount($row['idAccount']);
            $chatter->setIdPartener($row['idPartener']);

            return $chatter;
        } catch (\PDOException $exception) {
            throw new PartnerNotFoundException();
        }
    }

    /**
     * @param $fromUserId
     * @param $toUserId
     * @return string
     * @throws GetMessagesException
     */
    public function fetchUserChatHistory($fromUserId, $toUserId): string
    {
        try {
            $sqlQuery = $this->connection->prepare("SELECT * FROM Chatter  WHERE (idAccount=? AND idPartener=?) OR (idAccount=? AND idPartener = ?)  ORDER BY date DESC;");
            $sqlQuery->execute(array($fromUserId, $toUserId, $toUserId, $fromUserId));
            $row = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

            $output = '<ul class="list-unstyled">';
            foreach ($row as $chatter) {
                $userName = '';
                if ($chatter["idAccount"] == $fromUserId) {
                    $userName = '<b class="text-success">You</b>';
                } else {
                    $userName = '<b class="text-danger">' . $chatter['name'] . '</b>';
                }
                $output .= '
                      <li style="border-bottom:1px dotted #ccc">
                       <p>' . $userName . ' - ' . $chatter["message"] . '
                        <div align="right">
                         - <small><em>' . $chatter['date'] . '</em></small>
                        </div>
                       </p>
                      </li>
                      ';
            }
            $output .= '</ul>';
        } catch (\PDOException $exception) {
            throw new GetMessagesException();
        }

        return $output;
    }

    /**
     * @param int $id
     * @param string $username
     */
    public function getChatPage(int $id, string $username)
    {
        $statement = $this->connection->prepare("SELECT * FROM Chatter WHERE idAccount = ?");
        $statement->execute(array($id));
        $result = $statement->fetchAll();

        foreach ($result as $row) {
            $output .= '
               <div id="user_dialog_';
            $output .= $id;
            $output .= '"';
            $output .= '
               class="user_dialog" title="You have a chat with ';
            $output .= $username;
            $output .= '"';
            $output .= '
               <div style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;" class="chat_history" data-touserid="';
            $output .= $id;
            $output .='" id="chat_history_';
            $output .= $id;
            $output .='"
                </div>;
               <div class="form-group">;
               <textarea name="chat_message_';
            $output .= $id;
            $output .='" id="chat_message_';
            $output .= $id;
            $output .='" class="form-control"></textarea>;
               </div><div class="form-group" align="right">;
               <button type="button" name="send_chat" id="send_chat" class="btn btn-info send_chat">Send</button></div></div>;
                 ';
        }

        echo $output;
    }
}
