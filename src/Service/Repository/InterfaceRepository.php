<?php

namespace Team1\Service\Repository;

use Team1\Entity\User;

/**
 * Interface InterfaceRepository
 * @package Team1\Service\Repository
 */
interface InterfaceRepository
{
    /**
     * @param User $user
     * @return User
     */
    public function add(User $user): User;

    /**
     * @return User[]
     */
    public function getAll(): array;

    /**
     * @param User $user
     * @return User
     */
    public function update(User $user): User;

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);
}
