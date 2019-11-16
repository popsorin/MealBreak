<?php

/*
 * Written by Pop Sorin
 */

namespace Team1\Api\Data\Request;

/**
 * Class CreateRequest
 * @package Team1\Api\Data\Request
 */
class CreateRequest implements Request
{

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
    private $email;

    /**
     * @var boolean
     */
    protected $confirmed;

    /**
     * CreateRequest constructor.
     * @param string $name
     * @param string $password
     * @param string $email
     */
    public function __construct(
        string $name,
        string $password,
        string $email)
    {
        $this->password=$password;
        $this->name=$name;
        $this->email = $email;
        $this->confirmed = false;
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return bool
     */
    public function getIsConfirmed()
    {
        return $this->confirmed;
    }
}
