<?php


namespace Team1\Api\Controller;

use Team1\Api\Data\Request\ConfirmRequest;
use Team1\Exception\Persistency\EmailAlreadyUsedException;
use Team1\Exception\Persistency\InsertionFailedException;
use Team1\Exception\Persistency\NameAlreadyExistsException;
use Team1\Service\Builder\UserBuilder;
use Team1\Api\Data\Request\CreateRequest;
use Team1\Entity\User;
use Team1\Service\Repository\UserRepository;

/**
 * Class RegisterController
 * @package Team1\Api\Controller
 */
class RegisterController
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * RegisterController constructor.
     */
    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    /**
     * @return User[]
     */
    public function getAll(): array
    {
        $this->repository->getAll();
    }

    /**
     * @param CreateRequest $request
     * @throws EmailAlreadyUsedException
     * @throws InsertionFailedException
     * @throws NameAlreadyExistsException
     */
    public function add(CreateRequest $request)
    {
        $user = UserBuilder::buildUser($request);
        $this->repository->add($user);
    }

    /**
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->repository->delete($id);
    }

    /**
     * @param ConfirmRequest $request
     */
    public function update(ConfirmRequest $request)
    {
        $user = UserBuilder::buildUser($request);
        $this->update($user);
    }

    /**
     * @param string $file
     */
    public function displayHTML(string $file)
    {
        $myfile = file_get_contents($file);
        echo $myfile;
    }
    public function displayCSS($file)
    {
        $myfile = file_get_contents($file);
        $myfile = "<style>" . PHP_EOL . $myfile . "\n" . "</style>";
        echo $myfile;
    }
/*
    public function searchEmail(CreateRequest $request)
    {
        $user = UserBuilder::buildUser();
        $this->repository->searchEmail($user);
    }*/
}