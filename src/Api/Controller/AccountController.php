<?php
/*
 * Written by Andrei
 * Written by Alex
 */


namespace Team1\Api\Controller;

use Team1\Api\Data\Request\LoginRequest;
use Team1\Api\Data\Request\Request;
use Team1\Api\Data\Request\UpdateRquest;
use Team1\Entity\Account;
use Team1\Entity\HasId;
use Team1\Exception\Persistency\AccountNotFoundException;
use Team1\Exception\Persistency\ConnectionLostException;
use Team1\Exception\Persistency\EmailAlreadyUsedException;
use Team1\Exception\Persistency\InsertionFailedException;
use Team1\Exception\Persistency\NameAlreadyExistsException;
use Team1\Exception\Persistency\SearchAccountFailedException;
use Team1\Service\Repository\AccountRepository;
use Team1\Service\Builder\AccountBuilder;

/**
 * Class AccountController
 * @package Team1\Api\Controller
 */
class AccountController extends Controller
{
    /**
     * @var AccountRepository
     */
    private $repository;

    /**
     * AccountController constructor.
     * @throws ConnectionLostException
     */
    public function __construct()
    {
        $this->repository = new AccountRepository();
    }

    /**
     * @param Request $request
     * @return HasId
     * @throws AccountNotFoundException
     * @throws SearchAccountFailedException
     */
    public function logIn(Request $request)
    {
        $account = AccountBuilder::buildAccount($request);
        return $this->repository->search($account);
    }

    /**
     * @param UpdateRquest $request
     */
    public function addAcc(UpdateRquest $request)
    {
        $account = AccountBuilder::buildAccount($request);
        $this->repository->add($account);
    }

    /**
     * @param int $id
     * @return HasId
     * @throws AccountNotFoundException
     * @throws SearchAccountFailedException
     */
    public function searchById(int $id): HasId
    {
        return $this->repository->searchById($id);
    }

    public function logOut(int $id)
    {
        return $this->repository->logOut($id);
    }

    /**
     * @param UpdateRquest $request
     * @throws EmailAlreadyUsedException
     * @throws InsertionFailedException
     * @throws NameAlreadyExistsException
     */
    public function update(UpdateRquest $request)
    {
        $account = AccountBuilder::buildAccount($request);
        $this->repository->update($account);
    }
}
