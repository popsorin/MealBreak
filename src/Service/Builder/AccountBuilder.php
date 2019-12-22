<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Service\Builder;

use Team1\Api\Data\Request\LoginRequest;
use Team1\Entity\Account;
use Team1\Api\Data\Request\Request;
use Team1\Api\Data\Request\UpdateRquest;

/**
 * Class AccountBuilder
 * @package Team1\Service\Builder
 */
class AccountBuilder implements Builder
{
    /**
     * @param LoginRequest $request
     * @return Account
     */
    public static function buildAccount(LoginRequest $request): Account
    {
            $account = new Account();
            $account->setEmail($request->getEmail());
            $account->setPassword($request->getPassword());

            return $account;
    }
}
