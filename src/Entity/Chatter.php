<?php
/*
 * Written by Pop Sorin
 */
namespace Team1\Entity;

/**
 * Class Chatter
 * @package Team1\Entity
 */
class Chatter extends HasId
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $date;

    /**
     * @var int
     */
    private $idAccount;

    /**
     * @var int
     */

    private $idPartener;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getIdAccount()
    {
        return $this->idAccount;
    }

    /**
     * @param int $idAccount
     */
    public function setIdAccount($idAccount)
    {
        $this->idAccount = $idAccount;
    }

    /**
     * @return int
     */
    public function getIdPartener()
    {
        return $this->idPartener;
    }

    /**
     * @param int $idPartener
     */
    public function setIdPartener($idPartener)
    {
        $this->idPartener = $idPartener;
    }
}
