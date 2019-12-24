<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Service\Builder;

use Team1\Api\Data\Request\UpdateRquest;
use Team1\Entity\Account;
use Team1\Api\Data\Request\Request;

/**
 * Class AccountBuilder
 * @package Team1\Service\Builder
 */
class AccountBuilder
{
    /**
     * @param UpdateRquest $request
     * @return Account
     */
    public static function buildAccount(UpdateRquest $request): Account
    {
            $account = new Account();
            $account->setName($request->getName());
            $account->setEmail($request->getEmail());
            $account->setPassword($request->getPassword());
            $account->setDescription("");
            $account->setFirstName("");
            $account->setLastName("");
            $account->setIsOnline(0);
            $account->setQueuedStartTime("");

            return $account;
    }
}