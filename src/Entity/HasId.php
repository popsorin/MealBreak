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
    private $id;

    /**
     * @return int
     */
    public function getId()
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
