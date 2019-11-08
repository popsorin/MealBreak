<?php


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
    private $specific;

    /**
     * @var string
     */
    private $location;

    /**
     * @var stirng
     */
    private $boardgames;

    /**
     * @return stirng
     */
    public function getName()
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
    public function getSpecific()
    {
        return $this->specific;
    }

    /**
     * @param string $specific
     */
    public function setSpecific($specific)
    {
        $this->specific = $specific;
    }

    /**
     * @return string
     */
    public function getLocation()
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

    /**
     * @return stirng
     */
    public function getBoardgames()
    {
        return $this->boardgames;
    }

    /**
     * @param stirng $boardgames
     */
    public function setBoardgames($boardgames)
    {
        $this->boardgames = $boardgames;
    }
}
