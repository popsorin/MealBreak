<?php

namespace Team1\Api\Data\Response;

/**
 * Class Request
 * @package Team1\Api\Data\Request
 */
class Response
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $desctiption;

    /**
     * @var string
     */
    private $email;

    /**
     * Request constructor.
     * @param int $id
     * @param string $name
     * @param string $password
     * @param string $desctiption
     * @param string $email
     */
    public function __construct(int $id, string $name, string $password, string $desctiption, string $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->password = $password;
        $this->desctiption = $desctiption;
        $this->email = $email;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getDesctiption()
    {
        return $this->desctiption;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


}