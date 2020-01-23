<?php

/*
 * Written by Pop Sorin
 * Written by Alex Popa
 */

namespace Team1\Service\Builder;

use Team1\Api\Data\Request\LoginRequest;
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
     * @param Request $request
     * @return Account
     */
    public static function buildAccount(Request $request): Account
    {
            $account = new Account();
            $account->setEmail($request->getEmail());
            $account->setPassword($request->getPassword());
            $account->setIsOnline($request->getIsOnline());
        if ($request instanceof UpdateRquest) {
            $account->setName($request->getName());
            $account->setNickname($request->getNickname());
            $account->setIsConfirmed($request->getIsConfirmed());
            $account->setDescription($request->getDescription());
            $account->setQueueStartTime($request->getQueueStartTime());
            $account->setAge($request->getAge());
            $account->setId($request->getId());
            $account->setIsConfirmed(1);
        }


            return $account;
    }
}
