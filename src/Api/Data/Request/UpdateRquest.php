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
     * @param int $id
     * @param string $description
     * @param string $nickname
     */
    public function __construct(
        int $id,
        string $username,
        string $email,
        string $password,
        string $description,
        string $nickname,
        string $queueStartTime,
        int $isOnline,
        int $age
    ) {
        parent::__construct($username, $password, $email);
        $this->id = $id;
        $this->description = $description;
        $this->queueStartTime = $queueStartTime;
        $this->isOnline = $isOnline;
        $this->nickname = $nickname;
        $this->age = $age;
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
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }


}
