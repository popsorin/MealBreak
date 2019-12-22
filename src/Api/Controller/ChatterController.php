<?php
/*
 * Written by Pop Sorin
 */
namespace Team1\Api\Controller;

use Team1\Api\Data\Request\ConfirmRequest;
use Team1\Api\Data\Request\CreateRequest;
use Team1\Api\Data\Request\ChatterRequest;
use Team1\Api\Data\Request\MessageRequest;
use Team1\Entity\HasId;
use Team1\Entity\User;
use Team1\Exception\Persistency\AccountNotFoundException;
use Team1\Exception\Persistency\ConnectionLostException;
use Team1\Exception\Persistency\EmailAlreadyUsedException;
use Team1\Exception\Persistency\GetMessagesException;
use Team1\Exception\Persistency\InsertionFailedException;
use Team1\Exception\Persistency\NameAlreadyExistsException;
use Team1\Exception\Persistency\UpdateFailedException;
use Team1\Service\Builder\ChatterBuilder;
use Team1\Service\Builder\MessageBuilder;
use Team1\Service\Builder\UserBuilder;
use Team1\Service\Repository\ChatterRepository;
use Team1\Service\Repository\UserRepository;

/**
 * Class ChatterController
 * @package Team1\Api\Controller
 */
class ChatterController extends Controller
{
    /**
     * @var ChatterRepository
     */
    public $repository;

    /**
     * ChatterController constructor.
     * @throws ConnectionLostException
     */
    public function __construct()
    {
        $this->repository = new ChatterRepository();
    }

    /**
     * @return Chatter[]
     */
    public function getAll(): array
    {
        $this->repository->getAll();
    }

    /**
     * @param ChatterRequest $request
     * @return HasId
     * @throws InsertionFailedException
     */
    public function add(ChatterRequest $request): HasId
    {
        $chatter = ChatterBuilder::buildChatter($request);
        $this->repository->add($chatter);
    }

    /**
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->repository->delete($id);
    }

    /**
     * @param MessageRequest $request
     * @return HasId
     * @throws AccountNotFoundException
     * @throws UpdateFailedException
     */
    public function update(MessageRequest $request): HasId
    {
        $chatter = MessageBuilder::buildMessage($request);
        $this->repository->update($chatter);
    }

    /**
     * @param $fromUserId
     * @param $toUserId
     * @throws GetMessagesException
     */
    public function fetchUserChatHistory($fromUserId, $toUserId)
    {
        echo $this->repository->fetchUserChatHistory($fromUserId, $toUserId);
    }

    /**
     * @param $idAccount
     * @return mixed
     * @throws AccountNotFoundException
     */
    public function searchPartner($idAccount)
    {
        return $this->repository->searchPartner($idAccount);
    }
}
