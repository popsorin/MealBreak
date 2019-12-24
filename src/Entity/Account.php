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
    private $queued_start_time;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $queue_start_time;

    /**
     * @var boolean
     */
    private $isOnline;

    /**
     * @return string
     */
    public function getDescription()
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
    public function setQueuedStartTime($queued_start_time)
    {
        $this->queued_start_time = $queued_start_time;
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
}
