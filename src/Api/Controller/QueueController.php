<?php
/**
 *   Written by Popa Alexandru
 */

namespace Team1\Api\Controller;

use Team1\Exception\Persistency\ConnectionLostException;
use Team1\Service\Repository\QueueRepository;

class QueueController extends Controller
{
    /**
     * @var QueueRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new QueueRepository();
    }

    /**
     * RegisterController constructor.
     * @throws ConnectionLostException
     */
    public function getRepo()
    {
        return $this->repository;
    }

    /**
     * @return User[]
     */
    public function getAll(): array
    {
        $this->repository->getAll();
    }

    /**
     * @param int $account_id
     */
    public function add($account_id)
    {
        $this->repository->add($account_id);
    }

    /**
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->repository->delete($id);
    }

    /**
     * @param $account_id
     * @return mixed
     */
    public function tryToMatch($account_id)
    {
        $accounts_in_queue = $this->repository->getAll();
        if (!empty($accounts_in_queue)) {
            //die($match = $accounts_in_queue[0]);;
            foreach ($accounts_in_queue as $account) {
                if ($account->getAccountId() != $account_id) {
                    return $account;
                }
            }
        }
        return false;
    }
}
