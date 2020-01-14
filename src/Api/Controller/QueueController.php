<?php
/**
 *   Written by Popa Alexandru
 */

namespace Team1\Api\Controller;

use Team1\Exception\Persistency\ConnectionLostException;
use Team1\Service\Repository\QueueRepository;
use Team1\Service\Builder\QueuerBuilder;
use Team1\Api\Data\Request\QueuerRequest;
use Team1\Entity\Queuer;

class QueueController extends Controller
{
    /**
     * @var QueueRepository
     */
    private $repository;

    /**
     * QueueController constructor.
     */
    public function __construct()
    {
        $this->repository = new QueueRepository();
    }

    /**
     * QueueController constructor.
     * @throws ConnectionLostException
     */
    public function getRepo()
    {
        return $this->repository;
    }

    /**
     * @return Queuer[]
     */
    public function getAll(): array
    {
        $this->repository->getAll();
    }

    /**
     * @param QueuerRequest $request
     */
    public function add(QueuerRequest $request)
    {
        $queuer = QueuerBuilder::buildQueuer($request);
        $this->repository->add($queuer);
    }


    /**
     * @param $minimumNumberOfQueuers
     * @return bool
     */
    public function checkIfMinimumNumberSatisfied($minimumNumberOfQueuers)
    {
        return (count($this->repository->getAll()) >= $minimumNumberOfQueuers);
    }


    /**
     * @param $queuers_id
     * @return array
     */
    public function delete($queuers_id)
    {
        foreach ($queuers_id as $queuer_id) {
            $this->repository->delete($queuer_id);
        }
    }

    /**
     * @return int
     */
    public function selectQueuerToMatch()
    {
        $index =(int)floor(rand(1, 1000000)/1000000*count($this->repository->getAll()));
        return $index;
    }

    /**
     * @return array
     */
    public function getAccountsWithAge()
    {

        $sqlQuery = $this->repository->getConnection()->prepare("SELECT q.account_id,a.age FROM Queue q INNER JOIN (SELECT id, age FROM Account) a on a.id = q.account_id;");
        $sqlQuery->execute();
        $records = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);
        return $records;
    }

    /**
     * @return array
     */
    public function getMinAndMaxAge()
    {
        $minMax =array();
        $sqlQuery = $this->repository->getConnection()->prepare("SELECT MIN(a.age),MAX(a.age) FROM Queue q INNER JOIN (SELECT id, age FROM Account) a on a.id = q.account_id;");
        $sqlQuery->execute();
        $records = $sqlQuery->fetch(\PDO::FETCH_ASSOC);
        $minMax["min"] = $records["MIN(a.age)"];
        $minMax["max"] = $records["MAX(a.age)"];

        return $minMax;
    }

    /**
     * @param $account_id
     * @return array
     */
    public function getQueuerAge($account_id)
    {
        $sqlQuery = $this->repository->getConnection()->prepare("SELECT age FROM Account where id = ?");
        $sqlQuery->execute(array($account_id));
        $age = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);
        return $age;
    }

    /**
     * @return mixed
     */
    public function tryToMatch()
    {
        $accounts_in_queue = $this->repository->getAll();
        //get the index of the queuer that we will try to match
        $index = $this->selectQueuerToMatch();
        //creating the queuer object
        $queuerSelected = new Queuer();
        $queuerSelected->setId($accounts_in_queue[$index]->getId());
        $queuerSelected->setAccountId($accounts_in_queue[$index]->getAccountId());

        //we want the age of the queuer we want to match
        $age = $this->getQueuerAge($queuerSelected->getAccountId());

        //we need the range of age from the queuers for our matching algorithm
        $minMax = $this->getMinAndMaxAge();

        //array with account_id and age
        $matchArray = $this->getAccountsWithAge();

        //
        $maxValue = max((int)$age-(int)$minMax["min"], (int)$minMax["max"] -(int)$age);

        //array containing the 2 matched
        $chaters = array();
        for ($index =1; $index<=100; $index++) {
            $generatedAge = (int)floor(((rand(0, 1000000)/1000000) - (rand(0, 1000000)/1000000)) * (1 +abs((int)$maxValue -(int)$age)) + (int)$age);
            foreach ($matchArray as $partner) {
                if ((int)$generatedAge == (int)$partner["age"] && (int)$queuerSelected->getAccountId() !== (int)$partner["account_id"]) {
                    array_push($chaters, $partner["account_id"]);
                    array_push($chaters, $queuerSelected->getAccountId());
                    return $chaters;
                }
            }
        }

        return false;
    }
}
