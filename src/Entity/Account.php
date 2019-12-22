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
    private $desctiption;

    /**
     * @var string
     */
    private $nickname;

    /**
     * @var int
     */
    private $token;

    /**
     * @return string
     */
    public function getDesctiption(): string
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
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    /**
     * @return int
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param int $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }
}
