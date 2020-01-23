<?php

/*
 * Written by Popa Alexandru
 */

namespace Team1\Api\Data\Request;

/**
 * Class CreateRequest
 * @package Team1\Api\Data\Request
 */
class QueuerRequest implements Request
{

    /**
     * @var string
     */
    private $accountId;

    /**
     * CreateRequest constructor.
     * @param string $accountId
     */
    public function __construct(
        string $accountId
    ) {
        $this->accountId=$accountId;
    }

    /**
     * @return string
     */
    public function getAccountId(): string
    {
        return $this->accountId;
    }
}
