<?php

namespace Popsorin\Entity;

use Team1\Entity\User;

/**
 * Class Account
 * @package Popsorin\Entity
 */
class Account extends User
{
    /**
     * @var string
     */
    private $desctiption;

    /**
     * @var string
     */
    private $email;

    /**
     * @return string
     */
    public function getDesctiption()
    {
        return $this->desctiption;
    }

    /**
     * @param string $desctiption
     */
    public function setDesctiption($desctiption)
    {
        $this->desctiption = $desctiption;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
}
