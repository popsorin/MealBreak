<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Service\Builder;

use Team1\Entity\Account;
use Team1\Api\Data\Request\CreateRequest;
use Team1\Api\Data\Request\UpdateRquest;
use Team1\Entity\User;

/**
 * Class UserBuilder
 * @package Team1\Service\UserBuilder
 */
class UserBuilder implements Builder
{
    /**
     * @param CreateRequest $request
     * @return User
     */
    public static function buildUser(CreateRequest $request): User
    {
          $user = new User();
          $user->setName($request->getName());
          $user->setPassword($request->getPassword());
          $user->setEmail($request->getEmail());
          $user->setConfirmed($request->getIsConfirmed());

          return $user;
    }
}
