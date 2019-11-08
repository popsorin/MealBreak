<?php

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
     * @var boolean
     */
    private $isOnline;

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
    public function getNickname()
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
     * @return boolean
     */
    public function isOnline()
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


}
