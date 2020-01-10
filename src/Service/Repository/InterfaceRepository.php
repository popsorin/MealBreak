<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Service\Repository;

use Team1\Entity\HasId;

/**
 * Interface InterfaceRepository
 * @package Team1\Service\Repository
 */
interface InterfaceRepository
{
    /**
     * @param HasId $hasId
     * @return HasId
     */
    public function add(HasId $hasId): HasId;

    /**
     * @return HasId[]
     */
    public function getAll(): array;

    /**
     * @param HasId $hasId
     * @return HasId
     */
    public function update(HasId $hasId): HasId;

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);
}
