<?php
/*
 * Written by Andrei
 */

namespace Team1\Api\Data\Request;

class LoginRequest implements Request
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var int
     */
    private $token;

    /**
     * LoginRequest constructor.
     * @param string $email
     * @param string $password
     * @param int $token
     */
    public function __construct($email, $password, $token)
    {
        $this->email = $email;
        $this->password = $password;
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getToken()
    {
        return $this->token;
    }
}
