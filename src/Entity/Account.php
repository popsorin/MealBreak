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
}
