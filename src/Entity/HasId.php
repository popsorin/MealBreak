<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Entity;

/**
 * Class HasId
 * @package Team1\Entity
 */
class HasId
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}
