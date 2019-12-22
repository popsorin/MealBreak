<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Api\Data\Request;

/**
 * Class UpdateRquest
 * @package Team1\Api\Data\Request
 */
class UpdateRquest extends CreateRequest
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $nickname;

    /**
     * UpdateRquest constructor.
     * @param int $id
     * @param string $description
     * @param string $nickname
     */
    public function __construct($id, $description, $nickname)
    {
        $this->id = $id;
        $this->description = $description;
        $this->nickname = $nickname;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }
}
