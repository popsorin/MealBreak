<?php
/*
 * Written by Pop Sorin
 */

namespace Team1\Service\Builder;

use Team1\Api\Data\Request\ChatterRequest;
use Team1\Entity\Chatter;
use Team1\Service\Repository\InterfaceRepository;

/**
 * Class ChatterBuilder
 * @package Team1\Service\Builder
 */
class ChatterBuilder implements Builder
{
    /**
     * @param ChatterRequest $request
     * @return Chatter
     */
    public static function buildChatter(ChatterRequest $request): Chatter
    {
        $chatter = new Chatter();
        $chatter->setName($request->getName());
        $chatter->setDate($request->getDate());
        $chatter->setMessage($request->getMessage());
        $chatter->setIdAccount($request->getIdAccount());
        $chatter->setIdPartener($request->getIdPartener());
        $chatter->setPub($request->getPub());

        return $chatter;
    }
}
