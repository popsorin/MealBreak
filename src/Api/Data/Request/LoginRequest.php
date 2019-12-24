<?php
/*
 * Written by Andrei
 */

namespace Team1\Api\Data\Request;

class LoginRequest implements Request
{
    /**
     * @var email
     */
    private $email;

    /**
     * @var password
     */
    private $password;

    /**
     * LoginRequest constructor.
     * @param $user
     * @param $pass
     */
    public function __construct(
        $mail,
        $pass
    ) {
        $this->email = $mail;
        $this->password = $pass;
    }

    /**
     * @return email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return password
     */
    public function getPassword()
    {
        return $this->password;
    }
}
