<?php
/*
 * Written by Andrei
 */

namespace Team1\Api\Controller;


use Team1\Api\Data\Request\LoginRequest;
use Team1\Entity\Account;
use Team1\Service\Repository\AccountRepository;
use Team1Service\Builder\AccountBuilder;

class LoginController
{
    /**
     * @var AccountRepository
     */
    private $repository;

    /**
     * RegisterController constructor.
     */
    public function __construct()
    {
        $this->repository = new AccountRepository();
    }

    public function logIn(LoginRequest $request)
    {
        $account = AccountBuilder::buildAccount($request);
        $answer = $this->repository->search($account);

        return $answer;
    }

}