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
    public static function buildAccount(UpdateRequest $request): Account
    {
            $account = new Account();
            $account->setConfirmed(true);
            $account->setEmail($request->getEmail());
            $account->setDesctiption($request->getDescription());
            $account->setName($request->getName());
            $account->setNickname($request->getNickname());
            $account->setPassword($request->getPassword());
            $account->setIsOnline(true);

            return $account;
    }
}