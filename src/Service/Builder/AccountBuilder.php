<?php


namespace Team1Service\Builder;

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
     * @param UpdateRequest $request
     * @return Account
     */
    public static function buildAccount(Request $request): Account
    {
            $account = new Account();
            $account->setEmail($request->getEmail());
            $account->setPassword($request->getPassword());
            $account->setDescription("");
            $account->setName("");
            $account->setConfirmed(true);
            $account->setIsOnline(false);

            return $account;
    }
}