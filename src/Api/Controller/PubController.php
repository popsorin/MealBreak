<?php

namespace Team1\Api\Controller;

use Team1\Api\Data\Request\ChatterRequest;
use Team1\Api\Data\Request\DeleteChatterRequest;
use Team1\Entity\HasId;
use Team1\Entity\Pub;
use Team1\Exception\Persistency\AccountNotFoundException;
use Team1\Exception\Persistency\ConnectionLostException;
use Team1\Exception\Persistency\GetMessagesException;
use Team1\Exception\Persistency\InsertionFailedException;
use Team1\Exception\Persistency\PartnerNotFoundException;
use Team1\Exception\Persistency\ReturnAllFailedException;
use Team1\Exception\Persistency\UpdateFailedException;
use Team1\Service\Builder\ChatterBuilder;
use Team1\Service\Repository\ChatterRepository;
use Team1\Service\Repository\PubRepository;

/**
 * Class PubController
 * @package Team1\Api\Controller
 */
class PubController extends Controller
{
    /**
     * @var PubRepository
     */
    public $repository;

    /**
     * PubController constructor.
     * @throws ConnectionLostException
     */
    public function __construct()
    {
        $this->repository = new PubRepository();
    }

    /**
     * @return array
     * @throws ReturnAllFailedException
     */
    public function getAll(): array
    {
        $this->repository->getAll();
    }

    /**
     * @return int
     * @throws ReturnAllFailedException
     */
    public function randomPub(): int
    {
        $pubListId = array();
        $pubList = $this->repository->getAll();
        foreach ($pubList as $pub) {
                array_push($pubListId, $pub->getId());
        }

        $id = rand($pubListId[0], end($pubListId));

        return $pubList[$id-1]->getId();
    }

    public function getPub(int $id): Pub
    {
        return $this->repository->getAll()[$id-1];
    }
}
