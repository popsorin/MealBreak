<?php
/*
 * Written by Andrei
 */

namespace Team1\Api\Controller;

use Team1\Api\Data\Request\LoginRequest;
use Team1\Entity\Account;
use Team1\Exception\Persistency\AccountNotFoundException;
use Team1\Exception\Persistency\ConnectionLostException;
use Team1\Exception\Persistency\SearchAccountFailedException;
use Team1\Service\Repository\AccountRepository;
use Team1Service\Builder\AccountBuilder;

class LoginController extends Controller
{
    /**
     * @var AccountRepository
     */
    private $repository;

    /**
     * LoginController constructor.
     * @throws ConnectionLostException
     */
    public function __construct()
    {
        $this->repository = new AccountRepository();
    }

    /**
     * @param LoginRequest $request
     * @return Account
     * @throws AccountNotFoundException
     * @throws SearchAccountFailedException
     */
    public function logIn(LoginRequest $request)
    {
        $account = AccountBuilder::buildAccount($request);
        $answer = $this->repository->search($account);

        return $answer;
    }

}
