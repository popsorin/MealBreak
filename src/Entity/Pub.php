<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Entity;

/**
 * Class Pub
 * @package Team1\Entity
 */
class Pub extends HasId
{
    /**
     * @var stirng
     */
    private $name;
    /**
     * @var string
     */
    private $location;

    /**
     * @return stirng
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param stirng $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }
}
