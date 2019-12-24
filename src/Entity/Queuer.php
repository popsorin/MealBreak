<?php
/*
 * Written by Popa Alexandru
 */

namespace Team1\Entity;


class Queuer extends HasId
{
    /**
     * @var int
     */
    private $accountId;

    /**
     * @return int
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @param int $accountId
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
    }
}