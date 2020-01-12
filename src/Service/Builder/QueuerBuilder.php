<?php

/*
 * Written by Popa Alexandru
 */

namespace Team1\Service\Builder;

use Team1\Entity\Queuer;
use Team1\Api\Data\Request\QueuerRequest;

/**
 * Class QueuerBuilder
 * @package Team1\Service\QueuerBuilder
 */
class QueuerBuilder implements Builder
{
    /**
     * @param QueuerRequest $request
     * @return Queuer
     */
    public static function buildQueuer(QueuerRequest $request): Queuer
    {
        $queuer = new Queuer();
        $queuer->setAccountId($request->getAccountId());

        return $queuer;
    }
}