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
     * @var boolean
     */
    private $isOnline;

    /**
     * UpdateRquest constructor.
     * @param string $name
     * @param string $password
     * @param int $id
     * @param string $description
     * @param string $nickname
     * @param boolean $isOnline
     */
    public function __construct(
        string $name,
        string $password,
        int $id,
        string $description,
        string $nickname,
        boolean $isOnline
    ) {
        parent::__construct($name, $password);
        $this->id = $id;
        $this->description = $description;
        $this->nickname = $nickname;
        $this->isOnline = $isOnline;
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

    /**
     * @return boolean
     */
    public function getIsOnline()
    {
        return $this->isOnline;
    }
}
