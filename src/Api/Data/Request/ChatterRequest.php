<?php
/*
 * Written by Pop Sorin
 */

namespace Team1\Api\Data\Request;

/**
 * Class ChatterRequest
 * @package Team1\Api\Data\Request
 */
class ChatterRequest implements Request
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
     * @var int
     */
    private $pub;

    /**
     * ChatterRequest constructor.
     * @param string $name
     * @param string $message
     * @param string $date
     * @param int $idAccount
     * @param int $idPartener
     * @param int $pub
     */
    public function __construct(string $name, string $message, string $date, int $idAccount, int $idPartener, int $pub)
    {
        $this->name = $name;
        $this->message = $message;
        $this->date = $date;
        $this->idAccount = $idAccount;
        $this->idPartener = $idPartener;
        $this->pub = $pub;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getIdAccount()
    {
        return $this->idAccount;
    }

    /**
     * @return int
     */
    public function getIdPartener()
    {
        return $this->idPartener;
    }

    /**
     * @return string
     */
    public function getPub(): string
    {
        return $this->pub;
    }
}
