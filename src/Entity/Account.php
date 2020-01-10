<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Entity;

use Team1\Entity\User;

/**
 * Class Account
 * @package Team1\Entity
 */
class Account extends User
{
    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $nickname;

    /**
     * @var string
     */
    private $queue_start_time;

    /**
     * @var int
     */
    private $isOnline;

    /**
     * @var int
     */
    private $age;

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return boolean
     */
    public function getIsOnline()
    {
        return $this->isOnline;
    }

    /**
     * @param boolean $isOnline
     */
    public function setIsOnline($isOnline)
    {
        $this->isOnline = $isOnline;
    }

    /**
     * @return mixed
     */
    public function getQueueStartTime()
    {
        return $this->queue_start_time;
    }

    /**
     * @param mixed $queued_start_time
     */
    public function setQueueStartTime($queue_start_time)
    {
        $this->queue_start_time = $queue_start_time;
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     */
    public function setNickname(string $nickname)
    {
        $this->nickname = $nickname;
    }

    /**
     * @return int
     */
    public function getIsOnlie(): int
    {
        return $this->isOnlie;
    }

    /**
     * @param int $isOnlie
     */
    public function setIsOnlie(int $isOnlie)
    {
        $this->isOnlie = $isOnlie;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge(int $age)
    {
        $this->age = $age;
    }
}
