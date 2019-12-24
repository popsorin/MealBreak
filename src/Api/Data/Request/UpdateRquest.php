<?php

/*
 * Written by Pop Sorin & Popa Alexandru
 */

namespace Team1\Api\Data\Request;

/**
 * Class UpdateRquest
 * @package Team1\Api\Data\Request
 */
class UpdateRquest extends CreateRequest
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $queueStartTime;

    /**
     * @var int
     */
    private $isOnline;

    /**
     * UpdateRquest constructor.
     * @param string $name
     * @param string $password
     * @param int $id
     * @param string $description
     * @param string $nickname
     * @param int $isOnline
     */
    public function __construct(
        int $id,
        string $username,
        string $email,
        string $password,
        string $description,
        string $firstName,
        string $lastName,
        string $queueStartTime,
        int $isOnline
    ) {
        parent::__construct($username, $password, $email);
        $this->id = $id;
        $this->description = $description;
        $this->queueStartTime = $queueStartTime;
        $this->isOnline = $isOnline;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getQueueStartTime()
    {
        return $this->queueStartTime;
    }

    /**
     * @return int
     */
    public function getIsOnline()
    {
        return $this->isOnline;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }
}
