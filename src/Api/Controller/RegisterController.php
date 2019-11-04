<?php


namespace Team1\Api\Controller;

use Team1\Api\Data\Request\ConfirmRequest;
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
     * @return User
     */
    public function add(CreateRequest $request): User
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

    public function update(ConfirmRequest $request)
    {
        $user = UserBuilder::buildUser($request);
        $this->update($user);
    }

    public function displayHTML(string $file)
    {
        $myfile = file_get_contents($file);
        echo $myfile;
    }
}