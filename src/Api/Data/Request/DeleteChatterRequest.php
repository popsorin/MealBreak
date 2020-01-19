<?php

namespace Team1\Api\Data\Request;

/**
 * Class DeleteChatterRequest
 * @package Team1\Api\Data\Request
 */
class DeleteChatterRequest implements Request
{
    /**
     * @var int
     */
    private $idAccount;

    /**
     * @var int
     */
    private $idPartner;

    /**
     * DeleteChatterRequest constructor.
     * @param int $idAccount
     * @param int $idPartner
     */
    public function __construct(int $idAccount, int $idPartner)
    {
        $this->idAccount = $idAccount;
        $this->idPartner = $idPartner;
    }

    /**
     * @return int
     */
    public function getIdAccount(): int
    {
        return $this->idAccount;
    }

    /**
     * @return int
     */
    public function getIdPartner(): int
    {
        return $this->idPartner;
    }
}
